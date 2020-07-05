<?php
/**
 * KanbanItem Active Record
 * @author  <your-name-here>
 */
class KanbanItem extends TRecord
{
    const TABLENAME  = 'kanban_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('title');
        parent::addAttribute('content');
        parent::addAttribute('color');
        parent::addAttribute('item_order');
        parent::addAttribute('stage_id');
        parent::addAttribute('prazo');
        parent::addAttribute('prioridade');
        parent::addAttribute('data_inicial');
        
    }
}
