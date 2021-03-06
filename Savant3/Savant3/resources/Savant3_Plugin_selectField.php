<?php

class Savant3_Plugin_selectField extends Savant3_Plugin {

    /*
     * Other attributes managed by a simple foreach
     */
    public $_attr_other = array(
                        'id',
                        'class',
                        'onclick',
                        'onchange'
    );
	/**
	* 
	* Generate an HTML for SELECT field
        * Manage:
            * <select name="$name" ...
                <option value="$value" ...
	* 
	* @access public
	* 
	*/
	
	public function selectField($name, $attrs = array())
	{
        $html = "";
        
        // init ID Default Value: INPUT
        if(!isset($attrs["id"])) {
            $attrs["id"] = htmlspecialchars($name);
        }
        
        // check if ERRORS exists
        $hasError = isset($attrs["error"]) ? true : false;
        if($hasError) {
            $html .= '<div class="error">';
        }
        
        // set LABEL
        $label = isset($attrs["label"]) ? $attrs["label"] : "Set Label...";
        $html .= '<label for="'.htmlspecialchars($name).'">'.htmlspecialchars($label).':</label>'; // TODO: improve it with more kind of labels...
        
        // set SELECT
        $html .= '<select ';
        
        // set NAME
        if(isset($attrs["set_array"])) {
            $html .= ' name="'.htmlspecialchars($attrs["set_array"]).'['.htmlspecialchars($name).']"';
        } else {
            $html .= ' name="'.htmlspecialchars($name).'"';
        }
        
        // set OTHER ATTRIBUTES 
        foreach ($this->_attr_other AS $attribute) {
            if(isset($attrs[$attribute])) {
                $html .= ' '.$attribute.'="'.htmlspecialchars($attrs[$attribute]).'"';
            }
        }
        
        // REQUIRED
        if(isset($attrs["required"]) && $attrs["required"] === true) {
            $html .= ' required';
        }
        
        // DISABLED
        if(isset($attrs["disabled"]) && $attrs["disabled"] === true) {
            $html .= ' disabled';
        }

        // READONLY
        if(isset($attrs["readonly"]) && $attrs["readonly"] === true) {
            $html .= ' readonly';
        }
        

        // CLOSE SELECT tag
        $html .= '>';

        // set VALUE if it's defined by attributes
        $value = null;
        if(isset($attrs["value"]) && $attrs["value"] != "") {
            $value = $attrs["value"];
        }
        // echo "Value: $value<br>";

        // set OPTIONS
        if(isset($attrs["options"]) && is_array($attrs["options"])) {
            if(count($attrs["options"]) > 0 ) {
                foreach ($attrs["options"] as $optKey => $optVal) {
                    $selected = ($optKey == $value) ? " selected" : ""; 
                    $html .= '<option value="'.htmlspecialchars($optKey).'"'.$selected.'>'.htmlspecialchars($optVal).'</option>';
                }
            }
        }
        
        // CLOSE TAG
        $html .= '</select><br />';
        
        // ERRORS message
        if($hasError) {
            if( is_bool($attrs["error"]) ) {
                if( isset($attrs["errorMessage"]) && $attrs["errorMessage"] != "" ) {
                    $error = $attrs["errorMessage"];
                } else {
                    $error = 'Questo campo è obbligatorio!';
                }
            } else {
                $error = $attrs["error"];
            }
            $html .= "<p>" . $error . "</p></div>";
        }
		
		return $html;
	}
}