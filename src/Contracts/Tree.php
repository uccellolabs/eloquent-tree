<?php

namespace Uccello\EloquentTree\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface Tree
{
    /**
     * Set node as root node
     *
     * @return $this
     */
    public function setAsRoot();

    /**
     * Set node as child of $parent node
     *
     * @param Tree $parent
     *
     * @return $this
     */
    public function setChildOf(Tree $parent);

    /**
     * Validate if parent change and prevent self connection
     *
     * @param Tree $parent New parent node
     *
     * @return bool
     * @throws Exception\SelfConnectionException
     */
    public function validateSetChildOf(Tree $parent);

    /**
     * Set node as sibling of $sibling node
     *
     * @param Tree $sibling New sibling node
     *
     * @return $this
     */
    public function setSiblingOf(Tree $sibling);

    /**
     * Validate if parent change and prevent self connection
     *
     * @param Tree $sibling New sibling node
     *
     * @return bool
     */
    public function validateSetSiblingOf(Tree $sibling);

    /**
     * Check if node is root
     * This function check foreign key field
     *
     * @return bool
     */
    public function isRoot();

    /**
     * Check if node is sibling for passed node
     *
     * @param Tree $node
     *
     * @return bool
     */
    public function isSibling(Tree $node);

    /**
     * Get parent to specific node (if exist)
     *
     * @return static
     */
    public function parent();

    /**
     * Get children to specific node (if exist)
     *
     * @return static
     */
    public function children();

    /**
     * Find all descendants for specific node with this node as root
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findDescendants();

    /**
     * Find all ancestors for specific node with this node as leaf
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findAncestors();

    /**
     * Find root for this node
     *
     * @return $this
     */
    public function findRoot();

    /**
     * Rebuilds trees from passed nodes
     *
     * @param Collection $nodes  Nodes from which we are build tree
     * @param bool       $strict If we want to make sure that there are no orphan nodes
     *
     * @return static Root node
     * @throws MissingParentException
     */
    public function buildTree(Collection $nodes, $strict = true);

    /**
     * Displays a tree as html
     * Rendering function accept {sub-tree} tag, represents next tree level
     *
     * EXAMPLE:
     * $root->render(
     *    'ul',
     *    function ($node) {
     *       return '<li>' . $node->title . '{sub-tree}</li>';
     *   },
     *   TRUE
     * );
     *
     * @param string   $tag         HTML tag for level section
     * @param callable $render      Rendering function
     * @param bool     $displayRoot Is the root will be displayed
     *
     * @return string
     */
    public function render($tag, callable $render, $displayRoot = true);


    /**
     * Determine if we've already loaded the children
     * Used to prevent lazy loading on children
     *
     * @return bool
     */
    public function isChildrenLoaded();

    /**
     * Gets all root nodes
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getRoots();

    /**
     * Gets all leaf nodes
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getLeaves();

    /**
     * @param null $name
     *
     * @throws \Exception
     */
    public static function getTreeColumn($name = null);

    /**
     * Map array to tree structure in database
     * You must set $fillable attribute to use this function
     *
     * Example array:
     * array(
     *       'title'    => 'root',
     *       'children' => array(
     *                   array('title' => 'node1'),
     *                   array('title' => 'node2')
     *        )
     * );
     *
     * @param array $map Nodes recursive array
     */
    public static function mapArray(array $map);

    /**
     * Map array as descendants nodes in database to specific parent node
     * You must set $fillable attribute to use this function
     *
     * @param Tree  $parent Parent node
     * @param array $map    Nodes recursive array
     */
    public static function mapDescendantsArray(Tree $parent, array $map);
}
