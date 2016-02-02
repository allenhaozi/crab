<?php /* Smarty version 2.6.18, created on 2013-12-25 11:04:36
         compiled from Default/Curd/List.tpl */ ?>
<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['extraEach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['extraEach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['extraEach']['iteration']++;
?>
	<?php echo $this->_tpl_vars['item']['id']; ?>

<?php endforeach; endif; unset($_from); ?>