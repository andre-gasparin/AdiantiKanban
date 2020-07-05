<?php
use log\SystemChangeLogTrait;
use Adianti\Widget\Form\TButton;

/**
 * KanbanView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Artur Comunello
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class KanbanDatabaseView extends TPage
{
    private $form;
    private static $database = 'sistema';
    
    public function __construct($param)
    {

        parent::__construct();
        parent::include_css('app/resources/css/kanban.css');
        TTransaction::open('sistema');
        $criteria = new TCriteria;
        $criteria->setProperty('order', 'stage_order asc');
        $criteria->add(new TFilter('id_projeto', '=',  $param['key']));
        $stages = KanbanStage::getObjects( $criteria );
        TTransaction::close();

        $action = new TAction(['KanbanStageForm', 'onEdit'], ['register_state' => 'false']); 
        $action->setParameter('id_projeto', $param['key']);
        
        $addStage = new TActionLink('Nova Etapa', $action, '', null, null, 'far:check-circle green');
        $addStage->class = 'btn btn-default';
        $addStage->style = 'margin-left:10px;';
       

        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($addStage);
        parent::add($vbox);

        



        $kanban = new TKanban;
        foreach ($stages as $key => $stage)
        {
            $kanban->addStage($stage->id, $stage->title, $stage);

            TTransaction::open('sistema');
            $criteria = new TCriteria;
            $criteria->setProperty('order', 'item_order asc');
            $criteria->add(new TFilter('stage_id', '=',  $stage->id));
            $items = KanbanItem::getObjects( $criteria );
            TTransaction::close();
            foreach ($items as $key => $item)
            {

                $msgtodo = '';
                TTransaction::open('sistema');
                    $qtd_todo = ToDo::where('id_kanban_item','=', $item->id)->countBy('id', 'count');
                
                    if($qtd_todo > 0){
                        $qtd_todo_concluidos = ToDo::where('id_kanban_item','=',$item->id)->where('ativo', '=', 0)->countBy('id', 'count');
    
                        $horas = floor((strtotime( $item->prazo )- strtotime( $item->data_inicial)) / (60 * 60));
                        $horas_passados = floor((strtotime( date('Y-m-d H:i') )- strtotime( $item->data_inicial)) / (60 * 60));
                        
                        $todo_por_horas = @$horas / $qtd_todo;
    
                        $porcentagem = @$qtd_todo_concluidos*100 / $qtd_todo;
    
                        $quantidade_todo_esperada = floor($horas_passados/$todo_por_horas);//sempre arredondar pra baixo, pois ele vai estar no prazo do outro
    
                        if($quantidade_todo_esperada == $qtd_todo_concluidos)
                        { 
                            $msgtodo = 'No prazo'; 
                            $msgtodotooltip = '(Esperado:'.round($quantidade_todo_esperada).'/ Total:'.$qtd_todo.')'; 
                            $msgtodobadge = 'secondary';
                        }
                        if($quantidade_todo_esperada > $qtd_todo_concluidos) 
                        {
                            $msgtodo = 'Atrasado'; 
                            $msgtodotooltip = ' (Esperado:'.round($quantidade_todo_esperada).'/ Feito:'.$qtd_todo_concluidos.'/ Total:'.$qtd_todo.')'; 
                            $msgtodobadge = 'danger';
                        }
                        if($quantidade_todo_esperada < $qtd_todo_concluidos) {
                            $msgtodo = 'Indo bem'; 
                            $msgtodotooltip =  '(Esperado:'.round($quantidade_todo_esperada).'/ Feito:'.$qtd_todo_concluidos.'/ Total:'.$qtd_todo.')';
                            $msgtodotooltip =  $msgtodobadge = 'success';
                        }

                        $msgtodo = '<span class="badge badge-'.$msgtodobadge.'" style="cursor:pointer; text-shadow:none; float:right; font-size:10px; padding:3px; margin-left:5px;"  data-toggle="tooltip" data-placement="top" title="'. $msgtodotooltip.'">'.$msgtodo.'</span>';
                    }
                TTransaction::close();
                $corbadge = '';
                switch ($item->prioridade) {
                    case 'Baixa':
                        $corbadge = 'primary';
                        break;
                    case 'Média':
                        $corbadge = 'warning';
                        break;
                    case 'Urgente':
                        $corbadge = 'danger';
                        break;
                }
                
                $content = $item->content.'<br>'. $msgtodo.'<span class="badge badge-'.$corbadge.'" style="text-shadow:none; float:right; font-size:10px; padding:3px;">'.$item->prioridade.'</span>';
                $kanban->addItem($item->id, $item->stage_id, $item->title, $content, $item->color, $item);
            }


        }

        $kanban->addStageAction('Editar Etapa', new TAction(['KanbanStageForm', 'onEdit']),   'far:edit blue fa-fw');
        $kanban->addStageAction('Adicionar Item', new TAction(['KanbanItemForm', 'onStartEdit'], ['register_state' => 'false']),   'fa:plus green fa-fw');
        $kanban->addStageShortcut('Adicionar Item', new TAction(['KanbanItemForm', 'onStartEdit'], ['key_projeto'=>2, 'register_state' => 'false']),   'fa:plus fa-fw');
        
        $kanban->addItemAction('Editar Itesm', new TAction(['KanbanItemForm', 'onEdit'], ['key_projeto'=>2, 'register_state' => 'false']), 'far:edit bg-blue');
        $kanban->addItemAction('Deletar Item', new TAction([$this, 'onDelete']), 'far:trash-alt bg-red');
        
        //$kanban->setTemplatePath('app/resources/card.html');
        $kanban->setItemDropAction(new TAction([__CLASS__, 'onUpdateItemDrop']));
        $kanban->setStageDropAction(new TAction([__CLASS__, 'onUpdateStageDrop']));
        
        parent::add($kanban);
    }
    
    public function onLoad($param)
    {
    
    }
    public function onAtualiza(){
        new TMessage('info', "Registro salvo", null); 
    }
    
    /**
     * Update stage on drop
     */
    public static function onUpdateStageDrop($param)
    {
        if (empty($param['order']))
        {
            return;
        }
        
        try
        {
            TTransaction::open('sistema');
            
            foreach ($param['order'] as $key => $id)
            {
                $sequence = ++ $key;
    
                $stage = new KanbanStage($id);
                $stage->stage_order = $sequence;
    
                $stage->store();
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Update item on drop
     */
    public static function onUpdateItemDrop($param)
    {
        if (empty($param['order']))
        {
            return;
        }
        try
        {
            TTransaction::open('sistema');


                       
            // load customer, returns FALSE if not found
            $customer = KanbanStage::find($param['stage_id']);



            $novolog = new KanbanItemStageLog();
            $novolog->id_kanban_item = $param['id'];
            $novolog->data_modificacao = date("Y-m-d H:i:s");
            $novolog->titulo = 'Alteração de Etapa';
            $novolog->descricao = 'Movido para a etapa <strong>'.$customer->title.'</strong>';
            $novolog->store();

            foreach ($param['order'] as $key => $id)
            {
                $sequence = ++$key;
                
                $item = new KanbanItem($id);
                $item->item_order = $sequence;
                $item->stage_id = $param['stage_id'];
                $item->store();
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     *
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array(__CLASS__, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * method Delete()
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            // instantiates object and delete
            TTransaction::open('sistema');
            $object = new KanbanItem( $param['key'] );
            $object->delete();
            TTransaction::close();
            
            AdiantiCoreApplication::loadPage(__CLASS__, 'onLoad');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Item click
     */
    public static function onItemClick($param = NULL)
    {
        new TMessage('info', str_replace(',', '<br>', json_encode($param)));
    }
    
    /**
     * Display condition
     */
    public static function teste($param = NULL)
    {
        return TRUE;
    }
}