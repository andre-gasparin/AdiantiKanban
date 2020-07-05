<?php
use log\SystemChangeLogTrait;
use Adianti\Database\TTransaction;
use Adianti\Widget\Form\TDateTime;
use Adianti\Widget\Form\TText;
use Adianti\Widget\Wrapper\TQuickGrid;
use Adianti\Wrapper\BootstrapFormWrapper;

/**
 * KanbanItemForm
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class KanbanItemForm extends TPage
{
    protected $form; // form
    private static $database = 'sistema';
    private static $activeRecord = 'KanbanItem';
    private static $primaryKey = 'id';
    private static $formName = 'form_kanban_item';


    use Adianti\Base\AdiantiMasterDetailTrait;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct($param)
    {
        parent::__construct();
        parent::setTargetContainer("adianti_right_panel");
        
       

        // Criar os formulários
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle('Kanban item');
        


        $this->form->addAction( _t('Close'), new TAction([$this, 'onClose']), 'fa:times red')->style = 'float:right;';
        $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:save green')->style = 'float:right; margin-right:3px;';
 

        // Criar os campos do formulário
        $id          = new THidden('id');
        $title       = new TEntry('title');
        $content     = new TText('content');
        
        $color       = new TColor('color');
        $item_order  = new THidden('item_order');
        $stage_id    = new THidden('stage_id');

        //Passar a kye do projeto pro retorno
        $key_projeto    = new THidden('key_projeto');
        $key_projeto->setValue($param['key_projeto']);
       
        $prioridade= new TCombo('prioridade');
        $prioridade->addItems(['Baixa'=>'Baixa','Média' => 'Média', 'Urgente' => 'Urgente' ]);
        $prioridade->setValue('Baixa');

        $data_inicial= new THidden('data_inicial');
        $data_inicial->setValue( date('Y-m-d H:i') );

        $prazo= new TDateTime('prazo');
        $prazo->setMask('dd/mm/yyyy hh:ii');
        $prazo->setDatabaseMask('yyyy-mm-dd hh:ii');
        $prazo->setValue( date('Y-m-d') );

        //Cria Campos do detalhe
        $item_todo_nome = new TEntry('item_todo_nome');
        $item_todo_id = new THidden('item_todo_id');
        $item_todo_nome->setSize('80%');
        $item_todo_ativo =  new THidden('item_todo_ativo');


        $id->setEditable(FALSE);
        $title->setSize('100%');
        $title->placeholder = 'Título...';

        $color->setSize('100%');
        $color->placeholder = 'Cor';

        $content->setSize('100%', 100);
        $content->placeholder = 'Descrição...';
        
        // add the form fields
        $this->form->addFields( [$id, $data_inicial,$key_projeto] );
        $linha1 = $this->form->addFields([$title], [$color] );
        $linha1->layout = [' col-sm-6',' col-md-6'];
        $this->form->addFields( [new TLabel('Prazo',null,null, 'b')], [$prazo], [new TLabel('Prioridade',null,null, 'b')], [$prioridade] );
        $this->form->addFields( [$content] );
        $this->form->addFields( [$item_order] );
        $this->form->addFields( [$stage_id] );


        
        //Campos do detalhe
        //Cria botão do detalhe       
         $add_todo_item = new TButton('add_todo_item'); //Cria o botão
         $action_add_todo_item = new TAction(array($this, 'onAddTodoItem')); //cria a Action
         $add_todo_item->setAction($action_add_todo_item, ""); //coloca a action no botão
         $add_todo_item->setImage('fas:plus green');//insere icone no botão
         $add_todo_item->class = 'btn-add btn btn-default inline-button';
         TScript::create('setTimeout(function(){ $(".btn-add").attr("style", "margin-left: -10px; "); }, 100);');

        //DETALHES
        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->todo_item_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->todo_item_list->style = 'width:100%';
        $this->todo_item_list->class .= ' table-bordered';
        $this->todo_item_list->disableDefaultClick();
        //Adiciona Colunas para editar e deletar
        //Cria as colunas do detalhe, utilizando o mesmo nome que foi criado o campo lá encima
        $this->todo_item_list->addQuickColumn("Tarefas", 'item_todo_nome', 'left');
        //Cria model e adiciona a lista ao form
        $this->todo_item_list->createModel();

        //BOTÃO, se nada der certo voltar essa parte pra linha 73
       
        $this->form->addContent([$this->todo_item_list]);
        $this->form->addFields([new TLabel("Tarefa:", null, '14px', null)],[$item_todo_nome,$add_todo_item]);
        $this->form->addFields([$item_todo_id, $item_todo_ativo]);

        // define the form action
       // $this->form->addAction( _t('Close'), new TAction([$this, 'onClose']), 'fa:times red')->style = 'float:right;';
       // $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:save green')->style = 'float:right; margin-right:3px;';


        TScript::create("
            $(function() {
                $('.header-actions').html($('.panel-footer').html());
                $('.panel-footer').hide();
            });
        ");
       $this->form->addHeaderActionLink( _t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
       $this->form->addHeaderActionLink(_t('Save'), new TAction(array($this, 'onSave')), 'fa:save green');
           
        TScript::create('$("body").trigger("click")');
        TScript::create('$("[name=title]").focus()');

        parent::add($this->form);
    }
    /**
     *
     */
    public function onStartEdit($param)
    {
        TSession::setValue('item_todo_items', null);
        $data = new stdClass;
        $data->stage_id = $param['key'];
        $data->item_order = 999;
        $this->form->setData($data);
    }
    
    /**
     * Close side panel
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }

    public function onAddTodoItem($param){
        
        try
        {   
            //Pegar o conteúdo do form todo
            $data = $this->form->getData();
            //Pega minha session pra ver se tem outros valores
            $item_todo_items = TSession::getValue('item_todo_items');

            //Caso não tenha chave, ele cria uma
            if(isset($data->item_todo_id)) $novo = 1;
            $key = isset($data->item_todo_id) && $data->item_todo_id ? $data->item_todo_id : uniqid();
            $fields = []; 

            //monta a array só da parte do detalhe e joga com a chave de cima, criada ou existente
            $fields['item_todo_nome'] = $data->item_todo_nome;
            if($novo)
                $fields['item_todo_ativo'] = 1;
            else
                $fields['item_todo_ativo'] = $data->item_todo_ativo;
            $item_todo_items[ $key ] = $fields;

            TSession::setValue('item_todo_items', $item_todo_items);

            $data->item_todo_id = '';
            $data->item_todo_nome = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onReloadTodoItem($params = null)
    {
        $items = TSession::getValue('item_todo_items'); 

        $this->todo_item_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteItemTodo')); 
                $action_del->setParameter('item_todo_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $button_del = new TButton('delete_ItemTodo'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm float-right';
                $button_del->title = "Excluir";
                $button_del->setImage('fas:trash-alt #dd5a43');



                $action_edi = new TAction(array($this, 'onAtivaDesativaTodo')); 
                $action_edi->setParameter('item_todo_id', $key);
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_edi = new TButton('item_todo_ativo_btn'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                
                $button_edi->style = 'font-size:20px;';
                
                if($item['item_todo_ativo'] == 1){
                    $button_edi->setImage('fas:toggle-on #478fca');
                    $button_edi->title = "Desativar";
                    $nome  = $item['item_todo_nome'];
                }
                else
                {
                    $button_edi->setImage('fas:toggle-off #478fca');
                    $button_edi->title = "Ativar";
                    $nome  = '<span style="text-decoration: line-through;">'.$item['item_todo_nome'].'</span>';
                }
               

                $rowItem->item_todo_nome = isset($item['item_todo_nome']) ? $button_edi.' '.$nome .' '. $button_del : '';

                $row = $this->todo_item_list->addItem($rowItem);

                $cont++;
            } 
        } 
    }

    public function onAtivaDesativaTodo($param=null)
    {
        
        
        $data = $this->form->getData();
        $data->detalhe_principal_nome = '';
        // clear form data
        $this->form->setData( $data );
        // read session items
        $items = TSession::getValue('item_todo_items');
        // delete the item from session
        
        //Verifica se está ativo para desligar, e se estiver desativado liga
        $ativo = $items[$param['key']]['item_todo_ativo'] == 1 ? false : true;
        $items[$param['key']]['item_todo_ativo'] = $ativo;

        TSession::setValue('item_todo_items', $items);
        // reload sale items
        
        $this->onReload( $param );


      


    }
    
    public function onDeleteItemTodo($param=null)
    {

        $data = $this->form->getData();

        $data->detalhe_principal_nome = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('item_todo_items');

        // delete the item from session
        unset($items[$param['key']]);
        TSession::setValue('item_todo_items', $items);

        // reload sale items
        $this->onReload( $param );

    }

    public function onEditItemTodo($param=null)
    {
    
    }

    public function onReload($param = null)
    {
        $this->loaded = TRUE;
        $this->onReloadTodoItem($param);
        $this->addTimeline($param);
    }  

    public function onClear($param)
    {
        $this->form->clear(true);

        TSession::setValue('item_todo_items', null);

        $this->onReload();
    }

    public function show() 
    { 
        $param = func_get_arg(0);
        if(!empty($param['current_tab']))
        {
            $this->form->setCurrentPage($param['current_tab']);
        }

        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') ) 
        { 
            //$this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open('sistema'); // open a transaction

            $messageAction = null;

            $this->form->validate(); // Validar o formulário

            $object = new KanbanItem(); // Cria um objeto vazio

            $data = $this->form->getData(); // Pega todos os campos do form como array
                        
            $object->fromArray( (array) $data); // cria um objeto com o array

            $object->store(); // Salva o objeto  
             
            

            $item_todo = $this->storeItems('ToDo', 'id_kanban_item', $object, 'item_todo', function($masterObject, $detailObject){  }); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // completa o formulário

            TTransaction::close(); // close the transaction

            print_r($param);

            TScript::create("Template.closeRightPanel()");
            //register_state
            AdiantiCoreApplication::loadPage('KanbanDatabaseView','onAtualiza', ['key' => $param['key_projeto'], 'id' => $param['key_projeto']]);//['register_state'=>'false']
           

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onEdit($param=null)
    {
        try
        {
            
            TSession::setValue('item_todo_items', null);
            TTransaction::open(self::$database);
            $key = $param['key'];  // get the parameter $key
            $object = new KanbanItem($key); // instantiates the Active Record 
           
            if (isset($param['key']))
            {                         
                $this->toDo($param, $object);
                $this->form->setData($object); // fill the form 
                $this->onReload($param);
                
                // adiciona a timeline
            }
            else
            {
                $this->form->clear();
            }
            TTransaction::close(); // close the transaction 
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function toDo($param, $object){
        // open a transaction
        $qtd_todo = ToDo::where('id_kanban_item','=', $param['id'])->countBy('id', 'count');
                
        if($qtd_todo > 0){
            $qtd_todo_concluidos = ToDo::where('id_kanban_item','=', $param['id'])->where('ativo', '=', 0)->countBy('id', 'count');

            $horas = floor((strtotime( $object->prazo )- strtotime( $object->data_inicial)) / (60 * 60));
            $horas_passados = floor((strtotime( date('Y-m-d H:i') )- strtotime( $object->data_inicial)) / (60 * 60));
            
            $todo_por_horas = @$horas / $qtd_todo;

            $porcentagem = @$qtd_todo_concluidos*100 / $qtd_todo;

            $quantidade_todo_esperada = floor($horas_passados/$todo_por_horas);//sempre arredondar pra baixo, pois ele vai estar no prazo do outro

            if($quantidade_todo_esperada == $qtd_todo_concluidos)
            {
                $msgtodoclass = 'alert alert-primary';
                $msgtodo = 'Encima do prazo!<br> você fez as <strong>'.round($quantidade_todo_esperada).' tarefas</strong> que eram esperadas até o momento.';
            }
            if($quantidade_todo_esperada > $qtd_todo_concluidos)
            {
                $msgtodoclass = 'alert alert-danger';
                 $msgtodo = 'Atrasado!<br> Esperava que você tivesse feito <strong>'.round($quantidade_todo_esperada).' tarefas</strong> e você só completou <strong>'.$qtd_todo_concluidos.'</strong>!';
            }
            if($quantidade_todo_esperada < $qtd_todo_concluidos) 
            {
                $msgtodoclass = 'alert alert-success';
                $msgtodo = 'Indo bem!<br> Esperava que você tivesse feito <strong>'.round($quantidade_todo_esperada).' tarefas</strong> e você já completou <strong>'.$qtd_todo_concluidos.'</strong>!';
            }

            TScript::create('setTimeout(function(){ 
                $(".progress-bar").attr("style", "width:'.$porcentagem.'%; ").html("'.$porcentagem.'%"); 
                $(".info-msg-todo").html("'.$msgtodo.'").attr("class", "'.$msgtodoclass.' info-msg-todo");
            }, 500);');

            $bar = new TProgressBar;
            $bar->style = 'margin-top:20px;';            
            $info = new TElement('div');
            $info->class = 'info-msg-todo'; 
    
            parent::add($bar);  
            parent::add($info);
            
            //Cria lista de TODOS, não tem relação com o restante
            $item_todo = $this->loadItems('ToDo', 'id_kanban_item', $object, 'item_todo', function($masterObject, $detailObject, $objectItems){ 
                //code here
            });  
        }
    }
    public function addTimeline($param)
    {
       
        TTransaction::open(self::$database);   

        $criteria = new TCriteria;
        $criteria->setProperty('order', 'id asc');
        $criteria->add(new TFilter('id_kanban_item', '=',  $param['id']));
        $items = KanbanItemStageLog::getObjects( $criteria );
        
        TTransaction::close();

        if(count($items))
        {
            $timeline = new TTimeline;
            foreach ($items as $key => $item)
            {
                $timeline->addItem($item->id, $item->titulo,  $item->descricao, $item->data_modificacao,  'fa:arrow-right bg-green',  'right');
            }
            $timeline->setTimeDisplayMask('dd/mm/yyyy');
            $timeline->setFinalIcon( 'fa:flag-checkered bg-red' );

            $timelinediv = new TElement('div');
            $timelinediv->style = 'margin:20px;'; 
            $timelinediv->add( $timeline );
            parent::add($timelinediv);
        }
        
    }
    
}
