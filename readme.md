# HtmlNode Documentation

[![Build Status](https://travis-ci.org/ronan-gloo/html-node.png)](https://travis-ci.org/ronan-gloo/html-node)

HtmlNode is a "bootsrap library" to create and manipulate html elements in PHP.  
**Requires PHP 5.4+** 

#### Creation and options

Create your first node:

    $node = new Node("h1", "Hello World", ["class" => "title"]);
    
    

#### Node tag

Change or get the tag of the node:

    // Setter:
    $node->tag("h2");
    // Getter:
    $node->tag();
    // Ask if the node is an autoclosed element:
    $node->autoclose();
    
    

#### Node text Object

Setting text on autoclosed elements (input, meta... etc) will throw an `LogicException`

    $node->text("Chapter 10");
    // The getter
    $node->text();
    // Check if text contains the string:
    $node->contains("Chapter");
    // $2: case insensitive or not. $3: type checking
    $node->contains(10, true, true);
    // Replace part of the text
    $node->text->replace("10", "20");
    // Check if text match the expression
    $node->text->match("/^d%2B$/");
    // Text length
    $node->text->length();
    
    // moving text in collection
    $child = (new Node)->appendTo($node);
    $node->text->after($child);
    $node->text->before($child);
    
    // text is the first / last element in childs
    $node->text->first();
    $node->text->last();
    
    

#### Node attributes

Once the node is instanciated, its easy to customize it. Several methods allow you to set options in the "jQuery way".

    $node->attr("rel", "tooltip");
    // With an array
    $node->attr(["rel" => "tooltip", "id" => "hello-world"]);
    // Get an attribute
    $node->attr("id");
    
    

Search against an attribute. It is a very basic support, only `:` `.` and `#` expressions are supported:

    $node->is(":disabled");
    $node->is(".active");
    $node->not("h1");
    
    

`data` and `aria` attributes custom supports:

    $node->data("position", "left");
    // multi-level setter
    $node->data(["position" => ["before" => "left", "after" => "right"]]);
    // An other way is to make use of dotted notation
    $node->data("position.before", "left");
    // It also workds with the getter
    $node->data("position.before");
    
    

Add / remove classes.

    $node->addClass("active");
    // With an array
    $node->addClass(["active", "title"])
    // conditionnal: value not false in second parameter determines if the class should be added
    $node->addClassIf("active", true)
    // With 3 parameters, the class is added if third $2 match $3
    $node->removeClassIf("active", true, false) // nothing is added
    
    

Css inline styles:

    $node->css("color", "red");
    // With an array
    $node->css(["color" => "red", "margin" => 20])
    // With an array
    $node->css(["color" => "red", "margin" => 20])
    
    

#### Node Manipulation

Wrap or unwrap Node into a new node.

    $node->wrap("hgroup", ["class" => "group"]);
    $node->unwrap();
    
    

If you want to put nodes into an another node, use `append()` or `prepend()` instead:

    // Append to an existing node
    $node->appendTo($parent);
    // An other way to achieve this is
    $parent->append($node);
    // Or creates node on-the-fly
    $node->prependTo("hgroup", [["class" => "group"]);
    
    

Note that **the moved node is a clone**, the original node modifications wil not be proagated. So if you want to store the new node, do this:

    $appended = $node->appendTo($parent);
    
    

You can also insert nodes to the parent, with `insertBefore()` or `insertAfter()` methods:

    $new = (new Node("h3"))->insertAfter($appended);
    // Detach node from $parent
    $new->detach();
    
    

Replace a node with an other:

    $new = new Node("h3");
    $node->replaceWith($new);
    
    

#### Node traversing

A set of node childrens is a collection object which implements ArrayAccess and Tarversable in ordre to manipulate the collection:

    // Loop throught node childs:
    if ($parent->hasChildren()) {
      foreach ($parent->children() as $key => $child) {
        $child->addClassIf("active", $key % 2);
      }
    }
    // Get the parent:
    if ($child->hasParent()) {
      $parent = $child->parent();
    }
    // You can also check for the child:
    if ($parent->isParentOf($child)) {
      $child->detach();
    }
    // And the parent:
    if (! $child->isChildOf($parent)) {
      $parent->append($child);
    }
    
    

Get the current position of a child (returns an int):

    $child->index();
    // or from the parent node
    $parent->children->indexOf($child);
    
    

Get the child by its position in the collection:

    $child = $parent->children()->eq(2);
    
    

Visits node siblings inside a collection:

    if ($sibling = $child->next()) {
      $child = $silbing->prev();
    }
    
    

There is different ways to get a collection of siblings:

    // The collection, whithout the node:
    $siblings = $child->siblings();
    // All next / prev silbings
    $na = $child->nextAll();
    $pa = $child->prevAll();
    
    

#### Node iterations and search

    $list = Node::make("ul");
    $item = Node::make("li");
    // Populate the list:
    foreach ($items as $key => $val) {
      $temp = $item->appendTo($list)->addClassIf("even", $key % 2);
      // Set only 1 foo:
      if ($key == 4) {
        $anchor = Node::make("a", "foo")->appendTo($temp);
      }
    }
    
    

Now, we can start to get collections from our elements:

    // Find all  tags:
    $a = $list->find("a");
    // Find by attribute name:
    $a = $list->find("[required]");
    // Find by name / value:
    $a = $list->find('[name="email"]');
    // find childrens with class .even
    $e = $list->children(".even");
    // or a single result (a node)
    $c = $anchor->closest("ul");
    // find in next / prev
    $p = $temp->prevAll(".even");
    
    

#### Node rendering

Node object can be echoed, the `__toString` method calls `render()`

    // Render a node with all its contents:
    echo $node->render();
    // or simply:
    echo $node;
    // Render the node html (the text is skipped):
    echo $node->html();
    // Render the node contents (node children %2B text)
    echo $node->contents();
    // Render the node, whithout its html / text
    echo $node->self();
    
    

#### Build your own nodes !

Register a custom node instance, that you can re-use later. Add `true` as third parameter in order to prevent further instanciation (act as a singleton):

    // register the	custom node:
    Node::macro("input", function($name, $value = null, $attrs = []){
      return Node::make("input", compact("name", "value") %2B $attrs);
    });
    // use it:
    Node::input("email", null, ["placeholder" => "Email"]); 
