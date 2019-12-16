<?php
declare(strict_types = 1);

namespace Library;

class Toolkit {

   private static $instance;
   private static $count = 0;
	private $name;
	private $defaultName; // If user not provided a tag name or if it is invalid use this.
	private $attributeList;
	private $tagContentBeforeC;
	private $tagContentAfterC;
	private $grandParent;
	private $parent;
	private $children;
	private $mainAttributes = ['class', 'id', 'style', 'href'];
	private $selfClosed = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
	private $validHtmlTagNames = [];
 
	public function __construct(string $name) {
		$this->name = $name;
		$this->attributesList = array();
		$this->grandParent = null;
		$this->parent = null;
		$this->children = array();
	}

	public function __call($name, $value) {
		return  "You have called $name";
	}

	public static function __callStatic($name, $value) {

	}

	public function __toString() {
		return 'Call Error. You have called a object as a string. Please check your syntax again.';
	}

	public static function createElement(string $name) : object {
		self::$instance = new self($name);
		return self::$instance;
	}

	private function validateTagName(string $name) : string {//If user enter empty'' that error will catch when validating final html
		//Tag name should be a html tag name. Custom tag names should not be accepted.
		$name = preg_replace('/[^a-z]/', '', trim(strtolower($name)));
		//Check that name available within global html tag names array

		return $name;		
	}

	private function validateAttributeName(string $name) : string {//If user enter empty'' that error will catch when validating final html
		//Attribute name should be a html Attribute name. Custom tag names should not be accepted.
		$name = preg_replace('/[^a-z-]/', '', trim(strtolower($name)));

		return $name;		
	}

	private function validateAttributeValue(string $value) : string {//String can only contain a-z,- characters only.
		//Attribute value should be a html Attribute value. Custom tag names should not be accepted.
		$value = preg_replace('/[^a-z1-9,-.]/', '', trim(strtolower($value)));
		$value = preg_replace('/[,]/', ' ', $value);
		
		return $value;
	}

	public function attribute(string $attribute, string $value) { // values should be comma seperated string. For each attribute, use this function each time. Only accept string.
		$mainAttributes = $this->mainAttributes;
		$attributeList = &$this->attributeList;
		$attribute = $this->validateAttributeName($attribute);

		if ($attribute != null) { 
			// && if attribute value should not previoulsy entered one. Check within $attributeList array
			$isMainAttribute = in_array($attribute, $mainAttributes);
			if (!$isMainAttribute) {
				$attributeValue = $this->validateAttributeValue($value);
				if ($attributeValue != null) {
					$attributeList[$attribute] = $attributeValue;
				} else {
					var_dump('Attribute value is empty.');die;
				}
			} else {
				var_dump('Given attribute is a main attribute. Please use relevant method.');die;
			}
		} else {
			var_dump('Attribute name is empty.');die;
		}

		return $this;
	}

	private function generateAttributeString(array $attrArray) : string {
		$attrString = '';
		foreach ($attrArray as $attribute => $value) {
			$attrString .= $attribute . '="' . $value . '" ';
		}

		return $attrString;
	}

	// public function setTagName(string $name) {
	// 	$name = strtolower($name);
	// 	$this->name = $name;
	// }

	//Need to verify user given tag name is a valid html tag name

	private function isSelfClosed(string $name) : bool {
		$selfClosed = $this->selfClosed;
		return in_array($name, $selfClosed, true);
	}

	/**
	 * Validate the user entered text. Prevent from XSS attack. 
	 * 
	 * @param  string $text 
	 * @return string       
	 */
	private function validateXSS(string $text) : string {
		return htmlspecialchars($text);
	}

	// You should be able to set styles to the given text such as bold,italic,underline,color,background color
	public function text(string $text, bool $textAfterChildren = false) {
		$text = $this->validateXSS($text);

		if ($textAfterChildren) {
         $tagContent = &$this->tagContentAfterC;
         $tagContent .= $text;
      } else {
         $tagContent = &$this->tagContentBeforeC;
         $tagContent .= $text;
      }

		return $this;
	}

	/**
	 * Create a new html element as a child element and add to the current html element.
	 * 
	 * @param  string $name [Child element name]
	 * @return object [Child html element object]
	 */
	public function child(string $name) : object {
		$child = new self($name);
		Toolkit::$count ++;
      $this->children[(string)Toolkit::$count] = $child;
		$child->parent = $this;
		if ($this->grandParent === null) {
			$child->grandParent = &$this;
		} else {
		  	$child->grandParent = &$this->grandParent;
		}

		return $child;
	}

	public function getParent() : object {
		return $this->parent;
	}

	public function getChild(int $child) : object {
	   $child = (string)$child;
	   $children = $this->children;
	   if (array_key_exists($child, $children)) {
         return $children[$child];
      } else {
	      var_dump('This element doesn\'t have such number of child.');die();
      }
	}

	public function getTop() {
		return $this->grandParent;
	}

	/**
	 * Add previously created child element as a child to the current html element.
	 * 
	 * @param string $child [Child element name]
	 * @return object [Current html element object]
	 */
	public function addChild(string $child) : object {
		$content = &$this->tagContent;
		$content .= $child;

		return $this;
	}

	public function output() {
		$attributeList = $this->attributeList;
		$tagName = $this->validateTagName($this->name);
		$attributeList = $this->generateAttributeString($attributeList);
		$beforeContent = $this->tagContentBeforeC;
		$afterContent = $this->tagContentAfterC;
		$children = $this->children;
		$output = '';
      $childOutput = '';

		if ($tagName != null) {	
			$output	.= '<' . $tagName . ' ';
			$output .= $attributeList;
			if ($this->isSelfClosed($tagName)) {
				$output .= '/>';
			} else {
				$output .= '>';
            $output .= $beforeContent;
				if (!empty($children)) {
               foreach ($children as $key => $child) {
                  $childOutput .= $child->output();
               }
            }
            $output .= $childOutput;
            $output .= $afterContent;
				$output .= '</' . $tagName . '>';
			}
			return $output;
		} else {
			var_dump('Attribute name is empty.');die;
		}
	}
}