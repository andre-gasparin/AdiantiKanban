<?php
use log\SystemChangeLogTrait;
class KanbanStageForm extends TWindow
{
    protected $form; // form

    // trait with onSave, onClear, onEdit
    use Adianti\Base\AdiantiStandardFormTrait;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();
        parent::setSize(400, null);
        parent::removePadding();
        parent::setTitle('Etapa');
        
        $id_projeto_get = '';
        
        $this->setDatabase('sistema');    // defines the database
        $this->setActiveRecord('KanbanStage');   // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_City');
        
        // create the form fields
        $id          = new THidden('id');
        $id_projeto  = new THidden('id_projeto');
        $title       = new TEntry('title');
        $stage_order = new THidden('stage_order');
        $id->setEditable(FALSE);
        
        if(isset($param['id_projeto'])) $id_projeto_get = $param['id_projeto'];
        //$param['id_projeto'] = $param['id_projeto'] > 0 ? $param['id_projeto'] : $param['id_projeto']
        
        $id_projeto->setValue($id_projeto_get);
        // add the form fields
        $this->form->addFields( [$id, $id_projeto] );
        $this->form->addFields( [new TLabel('Nome', 'red')], [$title] );
        $this->form->addFields( [$stage_order] );
        
        // define the form action
        $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:save green');
        
        $action = new TAction( ['KanbanDatabaseView', 'onLoad'] );
        $action->setParameter('id', $id_projeto_get);
        $action->setParameter('key', $id_projeto_get);
        $this->setAfterSaveAction( $action );
        
        $this->setUseMessages(FALSE);
        
        TScript::create('$("body").trigger("click")');
        TScript::create('$("[name=title]").focus()');
        
        
        parent::add($this->form);
    }

}
