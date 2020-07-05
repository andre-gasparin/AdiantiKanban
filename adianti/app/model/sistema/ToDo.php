<?php
class ToDo extends TRecord
{
    const TABLENAME  = 'item_todo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
        parent::addAttribute('id_kanban_item');
    }


    public function set_kanbanitem(KanbanItem $object)
    {
        $this->kanbanitem = $object;
        $this->id_kanban_item = $object->id;
    }

    public function get_kanbanitem()
    {
        // loads the associated object
        if (empty($this->kanbanitem))
            $this->kanbanitem = new KanbanItem($this->id_kanban_item);
    
        // returns the associated object
        return $this->kanbanitem;
    }
}
