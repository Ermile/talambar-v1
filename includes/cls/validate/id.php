<?php
namespace cls\validate;

return function()
{
	// var_dump("id");
	// exit();
	if(!preg_match("/^\d+$/", $this->value))
		return false;
	else
		return true;
}
?>