
## Menu

A simple menu consists of a set of menu items.
A simple menu item has a title, a URI and optional attributes.

```php

$menu = (new \Cupcake\Menu\MenuManager())
    ->add('My title', '#')
    ->add('My other title', '#', ['target' => '_blank']);

$array = $menu->toArray();

// contents of $array
$array = [
    ['title' => 'My title', 'url' => '#', 'attr' => []],
    ['title' => 'My other title', 'url' => '#', 'attr' => ['_target' => 'blank']],
];


// or create Menu from array
$menu = \Cupcake\Menu\MenuManager::fromArray($array);
```

### Render a menu with the MenuHelper

```php 
// In a view template (or view class)
$this->loadHelper('Cupcake.Menu');

echo $this->Menu->create($menu, ['id' => 'mymenu', 'class'=>'main-menu')
    ->render();    
```
generates following HTML
```html 
<ul id="mymenu" class="main-menu">
    <li><a href="#">My title</a></li>
    <li><a target="_blank" href="#">My other title</a></li>
<ul>
```