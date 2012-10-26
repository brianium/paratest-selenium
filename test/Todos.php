<?php

class Todos
{
    private $session;
    public $input;

    public function __construct($session)
    {
        $this->session = $session;
        $this->input = $session->byId("new-todo");
    }

    public function getToggleAll()
    {
        return $this->session->byId("toggle-all");
    }

    public function addTodo($text)
    {
        $this->input->click();
        $this->session->keys($text . "\n");
    }

    public function addTodos($todos = array())
    {
        foreach($todos as $todo)
            $this->addTodo($todo);
    }

    public function getItems()
    {
        return $this->session->byId("todo-list")
                    ->elements($this->session->using('css selector')
                    ->value('li'));
    }

    public function getItemView($item)
    {
        return $item->element($this->session
                    ->using('css selector')
                    ->value('.view'));
    }

    public function getItemCheckbox($item)
    {
        return $item->element($this->session
                    ->using('css selector')
                    ->value('.view > .toggle'));
    }

    public function getItemEditField($item)
    {
        return $item->element($this->session
                    ->using('css selector')
                    ->value('.edit'));
    }

    public function getItemDestroy($index, $item)
    {
        //this is a css hover - and moveto doesn't seem to work
        $script  = "var list = document.getElementById('todo-list');";
        $script .= "var items = list.getElementsByTagName('li');";
        $script .= "var destroy = items[$index].getElementsByTagName('a')[0];";
        $script .= "destroy.style.display = 'block';";
        $this->session->execute(array(
            'script' => $script,
            'args' => array()
        ));
        return $item->element($this->session
                    ->using('css selector')
                    ->value('.view > .destroy'));
    }

    public function switchToEdit($index, $item)
    {
        //double click not implemented
        $script  = "var list = document.getElementById('todo-list');";
        $script .= "var item = list.getElementsByTagName('li')[$index];";
        $script .= "item.className = 'editing'";
        $this->session->execute(array(
            'script' => $script,
            'args' => array()
        ));
        $edit = $this->getItemEditField($item);
        $edit->clear();
        $edit->click();
    }

    public function getTodoCount()
    {
        return $this->session->byId("todoapp")
                    ->element($this->session->using('css selector')
                    ->value("footer > .todo-count"));
    }

    public function getMain()
    {
        return $this->session->byId("main");
    }

    public function getFooter()
    {
        return $this->session->byCssSelector("footer");
    }

    public function getClearButton()
    {
        return $this->session->byId("clear-completed");
    }
}