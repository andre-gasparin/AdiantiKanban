<?php
/**
 * KanbanItem Active Record
 * @author  <your-name-here>
 */
class KanbanItemStageLog extends TRecord
{
    const TABLENAME  = 'kanban_item_stage_log';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_kanban_item');
        parent::addAttribute('data_modificacao');
        parent::addAttribute('titulo');
        parent::addAttribute('descricao');
        
    }
}