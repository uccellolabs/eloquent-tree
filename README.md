eloquent-tree [![Latest Stable Version](https://poser.pugx.org/uccello/eloquent-tree/v/stable.png)](https://packagist.org/packages/uccello/eloquent-tree) [![Total Downloads](https://poser.pugx.org/uccello/eloquent-tree/downloads.png)](https://packagist.org/packages/uccello/eloquent-tree)
=============

Eloquent Tree transforms a model into tree model for Laravel Eloquent ORM.

This project is based on the original project made by [Adrian Skierniewski](https://github.com/AdrianSkierniewski/eloquent-tree). It was changed to be able to use this functionnality thanks to the `IsTree` trait, instead of extending the `Tree` model. It is useful if you want your model extends another class.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Migration](#migration)
- [Example usage](#example-usage)
- [Events](#events)
- [Support](#support)

##Features

* Creating root, children and sibling nodes
* Getting children
* Getting descendants
* Getting ancestor
* Moving sub-tree
* Building tree on PHP side


## Installation

**For Laravel >= 5.3**

Begin by installing this package through Composer. Edit your project's composer.json file to require uccello/eloquent-tree.
```json
"require": {
    "uccello/eloquent-tree": "1.*"
},
"minimum-stability" : "stable"
```
Next, update Composer from the Terminal:
```
composer update
```
That's all now you can extend \Gzero\EloquentTree\Model\Tree in your project

## Migration
Simply migration with all required columns that you could extend by adding new fields
```php
Schema::create(
    'trees',
    function (Blueprint $table) {
        $table->increments('id');
        $table->string('path', 255)->nullable();
        $table->integer('parent_id')->unsigned()->nullable();
        $table->integer('level')->default(0);
        $table->timestamps();
        $table->index(array('path', 'parent_id', 'level'));
        $table->foreign('parent_id')->references('id')->on('contents')->onDelete('CASCADE');
    }
);
```

## Example usage

- [Inserting and Updating new nodes](#inserting-and-updating-new-nodes)
- [Getting tree nodes](#getting-tree-nodes)
- [Finding Leaf nodes](#getting-leaf-nodes)
- [Map from array](#map-from-array)
- [Rendering tree](#rendering-tree)

### Inserting and updating new nodes

```php
$root = new Tree(); // New root
$root->setAsRoot();
$child = with(new Tree())->setChildOf($root); // New child
$sibling = new Tree();
$sibling->setSiblingOf($child); // New sibling
```

### Getting tree nodes

Leaf - returning root node
```php
$leaf->findRoot();
```

Children - returning flat collection of children. You can use Eloquent query builder.
```php
$collection = $root->children()->get();
$collection2 = $root->children()->where('url', '=', 'slug')->get();
```
Ancestors - returning flat collection of ancestors, first is root, last is current node. You can use Eloquent query builder.
            Of course there are no guarantees that the structure of the tree would be complete if you do the query with additional where
```php
$collection = $node->findAncestors()->get();
$collection2 = $node->findAncestors()->where('url', '=', 'slug')->get();
```

Descendants - returning flat collection of descendants, first is current node, last is leafs. You can use Eloquent query builder. Of course there are no guarantees that the structure of the tree would be complete if you do the query with additional where
```php
$collection = $node->findDescendants()->get();
$collection2 = $node->findDescendants()->where('url', '=', 'slug')->get();
```

Building tree structure on PHP side - if some nodes will be missing, these branches will not be built
```php
$treeRoot = $root->buildTree($root->findDescendants()->get())
```

### Getting leaf nodes
```php
Tree::getLeaves();
```

### Map from array

Three new roots, first with descendants
```php
 Tree::mapArray(
            array(
                array(
                    'children' => array(
                        array(
                            'children' => array(
                                array(
                                    'children' => array(
                                        array(
                                            'children' => array()
                                        ),
                                        array(
                                            'children' => array()
                                        )
                                    )
                                ),
                                array(
                                    'children' => array()
                                )
                            )
                        ),
                        array(
                            'children' => array()
                        )
                    )
                ),
                array(
                    'children' => array()
                ),
                array(
                    'children' => array()
                )
            )
 );
```

### Rendering tree

You can render tree built by the function buildTree
```php
 $html = $root->render(
        'ul',
        function ($node) {
            return '<li>' . $node->title . '{sub-tree}</li>';
        },
        TRUE
        );
 echo $html;
```

## Events

All tree models have additional events:
* updatingParent
* updatedParent
* updatedDescendants

You can use them for example to update additional tables


## Credits

- [Adrian Skierniewski][link-original-author]

- [Uccello Labs][link-organization]

- [Jonathan SARDO][link-author]

- [All Contributors][link-contributors]


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[link-original-project]: https://github.com/AdrianSkierniewski/eloquent-tree
[link-organization]: https://github.com/uccellolabs
[link-original-author]: https://github.com/AdrianSkierniewski
[link-author]: https://github.com/sardoj
[link-contributors]: ../../contributors
