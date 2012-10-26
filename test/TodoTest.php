<?php 

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Todos.php';

class TodoTest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $todos;

    public static $browsers = array(
        array(
            'browserName' => 'chrome'
        )
    );

    public function setUp()
    {
        $this->setBrowserUrl('http://backbonejs.org/examples/todos/');
        $this->todos = new Todos($this->prepareSession());
    }

    public function testTypingIntoFieldAndHittingEnterAddsTodo()
    {
        $this->todos->addTodo("parallelize phpunit tests\n");
        $this->assertEquals(1, sizeof($this->todos->getItems()));
    }

    public function testClickingTodoCheckboxMarksTodoDone()
    {
        $this->todos->addTodo("make sure you can complete todos");
        $items = $this->todos->getItems();
        $item = array_shift($items);
        $this->todos->getItemCheckbox($item)->click();
        $this->assertEquals('done', $item->attribute('class'));
    }

    public function testCheckingAllTodosMarksToggleAllAsChecked()
    {
        $this->todos->addTodos(array("one", "two"));
        foreach($this->todos->getItems() as $item)
            $this->todos->getItemCheckbox($item)->click();
        $this->assertEquals('true', $this->todos->getToggleAll()->attribute('checked'));
    }

    public function testCheckingAllTodosAndUncheckingOneWillUncheckToggleAll()
    {
        $this->todos->addTodos(array("one", "two"));
        foreach($this->todos->getItems() as $item)
            $this->todos->getItemCheckbox($item)->click();
        $items = $this->todos->getItems();
        $this->todos->getItemCheckbox(array_shift($items))->click();
        $this->assertNull($this->todos->getToggleAll()->attribute('checked'));
    }

    public function testClickingToggleAllWillMarkAllTodosAsComplete()
    {
        $this->todos->addTodos(array("three", "four"));
        $this->todos->getToggleAll()->click();
        foreach($this->todos->getItems() as $item)
            $this->assertEquals('done', $item->attribute('class'));
    }

    public function testClickingToggleAllAgainWillMarkAllTodosAsInComplete()
    {
        $this->todos->addTodos(array("three", "four"));
        $this->todos->getToggleAll()->click();
        $this->todos->getToggleAll()->click();
        foreach($this->todos->getItems() as $item)
            $this->assertNull($this->todos->getItemCheckbox($item)->attribute('checked'));
    }

    public function testAddingOneItemSetsCountToOneAndHasSingularTerm()
    {
        $this->todos->addTodo("make sure terms updated");
        $this->assertEquals("1 item left", $this->todos->getTodoCount()->text());
    }

    public function testAddingTwoItemsSetsCountToTwoAndHasPluralTerm()
    {
        $this->todos->addTodos(array('one', 'two'));
        $this->assertEquals("2 items left", $this->todos->getTodoCount()->text());
    }

    public function testCheckingItemsAsDoneSetsCountToZeroAndHasPluralTerm()
    {
        $this->todos->addTodo("make sure terms updated");
        $items = $this->todos->getItems();
        $todo = array_shift($items);
        $this->todos->getItemCheckbox($todo)->click();
        $this->assertEquals('0 items left', $this->todos->getTodoCount()->text());
    }

    public function testDestroyWillRemoveItem()
    {
        $this->todos->addTodo("make sure it can be destroyed");
        $todo = array_shift($this->todos->getItems());
        $this->todos->getItemDestroy(0, $todo)->click();
        $this->assertEquals(0, sizeof($this->todos->getItems()));
    }

    public function testDestroyOneOfTwoUpdatesCountText()
    {
        $this->todos->addTodos(array('one', 'two'));
        $items = $this->todos->getItems();
        $this->todos->getItemDestroy(0, array_shift($items))->click();
        $this->assertEquals('1 item left', $this->todos->getTodoCount()->text());
    }

    public function testDestroyLastItemHidesMainAndFooter()
    {
        $this->todos->addTodo("make sure main and footer are hidden");
        $items = $this->todos->getItems();
        $this->todos->getItemDestroy(0, array_shift($items))->click();
        $this->assertRegExp('/display: none;[\s]*/', $this->todos->getMain()->attribute('style'));
        $this->assertRegExp('/display: none;[\s]*/', $this->todos->getFooter()->attribute('style'));
    }

    public function testOneTodoCheckedShowsCorrectClearButtonText()
    {
        $this->todos->addTodos(array('one', 'two'));
        $items = $this->todos->getItems();
        $this->todos->getItemCheckbox(array_shift($items))->click();
        $this->assertEquals('Clear 1 completed item', $this->todos->getClearButton()->text());
    }

    public function testTwoTodosCheckedShowsCorrectClearButtonText()
    {
        $this->todos->addTodos(array('one', 'two'));
        $this->todos->getToggleAll()->click();
        $this->assertEquals('Clear 2 completed items', $this->todos->getClearButton()->text());
    }

    public function testClearButtonHidesMainAndFooter()
    {
        $this->todos->addTodos(array('one', 'two'));
        $this->todos->getToggleAll()->click();
        $this->todos->getClearButton()->click();
        $this->assertRegExp('/display: none;[\s]*/', $this->todos->getMain()->attribute('style'));
        $this->assertRegExp('/display: none;[\s]*/', $this->todos->getFooter()->attribute('style'));
    }

    public function testEditTodo()
    {
        $this->todos->addTodo("make sure todo is editable");
        $items = $this->todos->getItems();
        $todo = array_shift($items);
        $this->todos->switchToEdit(0, $todo);
        $this->keys("appears its fine\n");
        $this->assertEquals('appears its fine', $todo->text());
    }
}