<?php
/**
 * KanbanStage Active Record
 * @author  <your-name-here>
 */
class KanbanStage extends TRecord
{
    const TABLENAME  = 'kanban_stage';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('title');
        parent::addAttribute('stage_order');
        parent::addAttribute('id_projeto');
    }
}
