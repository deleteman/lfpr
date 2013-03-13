<?php
/**
 * Haml parser.
 *
 * @link http://haml.hamptoncatlin.com/ Original Haml parser (for Ruby)
 * @license http://www.opensource.org/licenses/mit-license.php MIT (X11) License
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 */

/**
 * Haml parser.
 * 
 * @link http://haml.hamptoncatlin.com/ Original Haml parser (for Ruby)
 * @license http://www.opensource.org/licenses/mit-license.php MIT (X11) License
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 */
class HamlParser
{
	/**
	 * Haml source
	 *
	 * @var string
	 */
	public $sSource = '';
	
	/**
	 * Files path
	 *
	 * @var string
	 */
	protected $sPath = '';
	
	/**
	 * Compile templates??
	 *
	 * @var boolean
	 */
	protected $bCompile = true;
	
	/**
	 * Filename
	 *
	 * @var string
	 */
	protected $sFile = '';
	
	/**
	 * Parent parser
	 *
	 * @var object
	 */
	protected $oParent = null;
	
	/**
	 * Children parsers
	 * 
	 * @var array
	 */
	protected $aChildren = array();
	
	/**
	 * Indent level
	 *
	 * @var integer
	 */
	public $iIndent = -1;
	
	/**
	 * Translation function name.
	 * 
	 * @var string
	 */
	public $sTranslate = 'fake_translate';
	
	/**
	 * Temporary directory
	 * 
	 * @var string
	 */
	protected $sTmp = '';
	
	/**
	 * Block of PHP code
	 * 
	 * @var boolean
	 */
	protected $bBlock = false;
	
	/**
	 * The constructor
	 * 
	 * @param string Path to files
	 * @param boolean/string Compile templates (can be path)
	 * @param object Parent parser
	 */
	public function __construct($sPath = false, $bCompile = true, $oParent = null)
	{
		if ($sPath)
			$this->setPath($sPath);
		$this->bCompile = $bCompile;
		if (is_string($bCompile))
			$this->setTmp($bCompile);
		else
			$this->setTmp($sPath);
		if ($oParent)
			$this->setParent($oParent);
	}
	
	/**
	 * Set parent parser
	 *
	 * @param object Parent parser
	 * @return object
	 */
	public function setParent($oParent)
	{
		$this->oParent = $oParent;
		return $this;
	}
	
	/**
	 * Set files path
	 *
	 * @param string File path
	 * @return object
	 */
	public function setPath($sPath)
	{
		$this->sPath = realpath($sPath);
		return $this;
	}
	
	/**
	 * Set filename
	 *
	 * @param string Filename
	 * @return object
	 */
	public function setFile($sPath)
	{
		if (file_exists($sPath))
			$this->sFile = $sPath;
		else
			$this->sFile = "{$this->sPath}/$sPath";
		$this->setSource(file_get_contents($this->sFile));
		return $this;
	}
	
	/**
	 * Return filename to include
	 * 
	 * @param string Name
	 * @return string
	 */
	public function getFilename($sName)
	{
		return "{$this->sPath}/$sName.haml";
	}

	/**
	 * Real source
	 * 
	 * @var string
	 */
	public $sRealSource = '';
	
	/**
	 * Set source code
	 *
	 * @param string Source
	 * @return object
	 */
	public function setSource($sHaml)
	{
		$this->sSource = trim($sHaml, self::TOKEN_INDENT);
		$this->sRealSource = $sHaml;
		return $this;
	}
	
	/**
	 * Set temporary directory
	 * 
	 * @param string Directory
	 * @return object
	 */
	public function setTmp($sTmp)
	{
		$this->sTmp = realpath($sTmp);
		return $this;
	}

	/**
	 * Render the source or file
	 *
	 * @return string
	 */
	public function render()
	{
		$__aSource = explode(self::TOKEN_LINE, $this->sSource = $this->parseBreak($this->sSource));
		$__sCompiled = '';
		if (count($__aSource) == 1)
			$__sCompiled = $this->parseLine($__aSource[0]);
		else
		{
			if (($__sC = $this->compiled()) && $this->bCompile)
				$__sCompiled = $__sC;
			else
			{
				$__iIndent = 0;
				$__iIndentLevel = 0;
				foreach ($__aSource as $__iKey => $__sLine)
				{
					$__iLevel = $this->countLevel($__sLine);
					if ($__iLevel <= $__iIndentLevel)
						$__iIndent = $__iIndentLevel = 0;
					if (preg_match('/\\'.self::TOKEN_LEVEL.'([0-9]+)$/', $__sLine, $__aMatches))
					{
						$__iIndent = (int)$__aMatches[1];
						$__iIndentLevel = $__iLevel;
						$__sLine = preg_replace('/\\'.self::TOKEN_LEVEL."$__iIndent$/", '', $__sLine);
					}
					$__sLine = str_repeat(self::TOKEN_INDENT, $__iIndent * self::INDENT) . $__sLine;
					$__aSource[$__iKey] = $__sLine;
					if (preg_match('/^(\s*)'.self::TOKEN_INCLUDE.' (.+)/', $__sLine, $aMatches))
					{
						$__sIncludeSource = $this->sourceIndent(file_get_contents($this->getFilename($aMatches[2])), $__iIndent ? $__iIndent : $__iLevel);
						$__sLine = str_replace($aMatches[1] . self::TOKEN_INCLUDE . " {$aMatches[2]}", $__sIncludeSource, $__sLine);
						$__aSource[$__iKey] = $__sLine;
						$this->sSource = implode(self::TOKEN_LINE, $__aSource);
					}
				}
				$__aSource = explode(self::TOKEN_LINE, $this->sSource = $this->parseBreak($this->sSource));
				$__sCompiled = $this->compile($this->parseFile($__aSource));
			}
			ob_end_flush();
			ob_start();
			foreach (self::$aVariables as $__sName => $__mValue)
				$$__sName = $__mValue;
			require $__sCompiled;
			$c = trim(ob_get_contents());
			foreach ($this->aFilters as $mFilter)
				$c = call_user_func($mFilter, $c);
			ob_clean();
			return $c;
		}
		return $__sCompiled;
	}
	
	/**
	 * Check for compiled template
	 * 
	 * @return string Compiled filename
	 */
	public function compiled()
	{
		if (!$this->bCompile)
			return false;
		$sSourceHash = '';
		if (function_exists('hash'))
			$sSourceHash = hash('md5', $this->sSource);
		else
			$sSourceHash = md5($this->sSource);
		$sFilename = "{$this->sTmp}/$sSourceHash.hphp";
		if (file_exists($sFilename))
			return $sFilename;
		else
			return false;
	}
	
	/**
	 * Save compiled template
	 * 
	 * @param string Compiled template
	 * @return string Filename
	 */
	public function compile($sCompiled)
	{
		$sSourceHash = '';
		if (function_exists('hash'))
		$sSourceHash = hash('md5', $this->sSource);
		else
			$sSourceHash = md5($this->sSource);
		$sFilename = "{$this->sTmp}/$sSourceHash.hphp";
		file_put_contents($sFilename, $sCompiled);
		return $sFilename;
	}

	/**
	 * Render the source or file
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}
	
	/**
	 * Parse multiline
	 * 
	 * @param string File content
	 * @return string
	 */
	public function parseBreak($sFile)
	{
		$sFile = preg_replace('/\\'.self::TOKEN_BREAK.'\s*/', '', $sFile);
		return $sFile;
	}
	
	/**
	 * Return source of child
	 * 
	 * @param integer Level
	 * @return string
	 */
	public function getAsSource($iLevel)
	{
		$x = ($this->iIndent - $iLevel - 1) * self::INDENT;
		$sSource = '';
		if ($x >= 0)
			$sSource = preg_replace('|^'.str_repeat(self::TOKEN_INDENT, ($iLevel + 1) * self::INDENT).'|', '', $this->sRealSource);
		foreach ($this->aChildren as $oChild)
			$sSource .= self::TOKEN_LINE.$oChild->getAsSource($iLevel);
		return trim($sSource, self::TOKEN_LINE);
	}
	
	/**
	 * Create and append line to parent
	 *
	 * @param string Line
	 * @param object Parent parser
	 * @return object
	 */
	public function createLine($sLine, $parent)
	{
		$oHaml = new HamlParser($this->sPath, $this->bCompile, $parent);
		$oHaml->setSource($sLine);
		$oHaml->iIndent = $parent->iIndent + 1;
		$parent->aChildren[] = $oHaml;
		return $oHaml;
	}

	/**
	 * Parse file
	 *
	 * @param array Array of source lines
	 * @return string
	 */
	protected function parseFile($aSource)
	{
		$aLevels = array(-1 => $this);
		$sCompiled = '';
		foreach ($aSource as $sSource)
		{
			$iLevel = $this->countLevel($sSource);
			$aLevels[$iLevel] = $this->createLine($sSource, $aLevels[$iLevel - 1]);
		}
		foreach ($this->aChildren as $oChild)
			$sCompiled .= $oChild->render();
		return $sCompiled;
	}
	
	/**
	 * List of text processing blocks
	 * 
	 * @var array
	 */
	protected static $aBlocks = array();
	
	/**
	 * Register block
	 * 
	 * @param mixed Callable
	 * @param string Name
	 * @return object
	 */
	public function registerBlock($mCallable, $sName = false)
	{
		if (!$sName)
			$sName = serialize($mCallable);
		self::$aBlocks[$sName] = $mCallable;
		return $this;
	}
	
	/**
	 * Unregister block
	 * 
	 * @param string Name
	 * @return object
	 */
	public function unregisterBlock($sName)
	{
		unset(self::$aBlocks[$sName]);
		return $this;
	}
	
	/**
	 * Parse text block
	 * 
	 * @param string Block name
	 * @param string Data
	 * @return string
	 */
	public function parseTextBlock($sName, $sText)
	{
		return call_user_func(self::$aBlocks[$sName], $sText);
	}
	
	/**
	 * Parse line
	 *
	 * @param string Line
	 * @return string
	 */
	public function parseLine($sSource)
	{
		$sParsed = '';
		$sRealBegin = '';
		$sRealEnd = '';
		$sParsedBegin = '';
		$sParsedEnd = '';
		$bParse = true;
		// Doctype parsing
		if (preg_match('/^'.self::TOKEN_DOCTYPE.'(.*)/', $sSource, $aMatches))
		{
			$aMatches[1] = trim($aMatches[1]);
			if ($aMatches[1] == '')
			  $aMatches[1] = '1.1';
			$sParsed = self::$aDoctypes[$aMatches[1]]."\n";
		} else
		// PHP instruction
		if (preg_match('/^'.self::TOKEN_INSTRUCTION_PHP.' (.*)/', $sSource, $aMatches))
		{
			$bBlock = false;
			// Check for block
			if (preg_match('/^('.implode('|', self::$aPhpBlocks).')/', $aMatches[1]))
			  $this->bBlock = $bBlock = true;
			$sParsedBegin = "<?php {$aMatches[1]}" . ($bBlock ? ' {' : ';') . " ?>\n";
			if ($bBlock)
			  $sParsedEnd = "<?php } ?>\n";
		} else
		// Text block
		if (preg_match('/^'.self::TOKEN_TEXT_BLOCKS.'(.+)/', $sSource, $aMatches))
		{
			$sParsed = $this->indent($this->parseTextBlock($aMatches[1], $this->getAsSource($this->iIndent)));
			$this->aChildren = array();
		} else
		// Check for PHP
		if (preg_match('/^'.self::TOKEN_PARSE_PHP.' (.*)/', $sSource, $aMatches))
			$sParsed = $this->indent("<?php echo {$aMatches[1]}; ?>\n");
		else
		{
			$aAttributes = array();
			$sAttributes = '';
			$sTag = 'div';
			$sToParse = '';
			$sContent = '';
			$sAutoVar = '';
			
			// Parse options
			preg_match('/\\'.self::TOKEN_OPTIONS_LEFT.'(.*?)\\'.self::TOKEN_OPTIONS_RIGHT.'/', $sSource, $aMatches);
			if (count($aMatches) > 1)
			{
				$sSource = str_replace($aMatches[0], '', $sSource);
				$aOptions = explode(self::TOKEN_OPTIONS_SEPARATOR, $aMatches[1]);
				foreach ($aOptions as $sOption)
				{
					$aOption = explode(self::TOKEN_OPTION_VALUE, trim($sOption));
					foreach ($aOption as $k => $o)
						$aOption[$k] = trim($o);
					$sOptionName = ltrim($aOption[0], self::TOKEN_OPTION);
					$aAttributes[$sOptionName] = $aOption[1];
				}
			}
			
			$sFirst = '['.self::TOKEN_TAG.'|'.self::TOKEN_ID.'|'.self::TOKEN_CLASS.'|'.self::TOKEN_PARSE_PHP.']';
			
			if (preg_match("/($sFirst.*?) (.*)/", $sSource, $aMatches))
			{
				$sToParse = $aMatches[1];
				$sContent = $aMatches[2];
			} else
			if (preg_match("/($sFirst.*)/", $sSource, $aMatches))
				$sToParse = $aMatches[1];
			else
			{
				// Check for comment
				if (!preg_match('/^\\'.self::TOKEN_COMMENT.'(.*)/', $sSource, $aMatches))
				{
					$sParsed = $this->indent($sSource);
					foreach ($this->aChildren as $oChild)
						$sParsed .= $oChild;
				}
				else
				{
					$aMatches[1] = trim($aMatches[1]);
					if ($aMatches[1] && !preg_match('/\[.*\]/', $aMatches[1]))
						$sParsed = $this->indent(wordwrap($aMatches[1], 60, "\n"), 1)."\n";
				}
				$bParse = false;
			}
			
			if ($bParse)
			{
				$bPhp = false;
				$bClosed = false;
				// Match tag
				if (preg_match('/^'.self::TOKEN_TAG.'([a-zA-Z0-9]*)/', $sToParse, $aMatches))
					$sTag = $aMatches[1];
				// Match ID
				if (preg_match('/'.self::TOKEN_ID.'([a-zA-Z0-9_]*)/', $sToParse, $aMatches))
					$aAttributes['id'] = "'{$aMatches[1]}'";
				// Match classes
				if (preg_match_all('/\\'.self::TOKEN_CLASS.'([a-zA-Z0-9_]*)/', $sToParse, $aMatches))
					$aAttributes['class'] = '\''.implode(' ', $aMatches[1]).'\'';
				// Check for PHP
				if (preg_match('/'.self::TOKEN_PARSE_PHP.'/', $sToParse))
				{
					$sContentOld = $sContent;
					$sContent = "<?php echo $sContent; ?>\n";
					$bPhp = true;
				}
				// Match translating
				if (preg_match('/\\'.self::TOKEN_TRANSLATE.'$/', $sToParse, $aMatches))
				{
					if (!$bPhp)
						$sContent = "'$sContent'";
					else
						$sContent = $sContentOld;
					$sContent = "<?php echo {$this->sTranslate}($sContent); ?>\n";
				}
				// Match single tag
				if (preg_match('/\\'.self::TOKEN_SINGLE.'$/', $sToParse))
					$bClosed = true;
				// Match brackets
				if (preg_match('/\\'.self::TOKEN_AUTO_LEFT.'(.*?)\\'.self::TOKEN_AUTO_RIGHT.'/', $sToParse, $aMatches))
					$sAutoVar = $aMatches[1];
					
				if (!empty($aAttributes) || !empty($sAutoVar))
					$sAttributes = '<?php $this->writeAttributes('.$this->arrayExport($aAttributes).(!empty($sAutoVar) ? ", \$this->parseSquareBrackets($sAutoVar)" : '' ).'); ?>';
				$this->bBlock = $this->oParent->bBlock;
				$iLevelM = $this->oParent->bBlock || $this->bBlock ? -1 : 0;
				// Check for block tag
				if (!$this->isInline($sTag) && !$this->isClosed($sTag) && !$bClosed)
				{
					$sParsedBegin = $this->indent("<$sTag$sAttributes>", $iLevelM);
					if (!empty($sContent))
						if (strlen($sContent) > 60)
							$sParsed = $this->indent(wordwrap($sContent, 60, "\n"), 1+$iLevelM);
						else
							$sParsed = $this->indent($sContent, 1+$iLevelM);
					$sParsedEnd = $this->indent("</$sTag>", $iLevelM);
				} else
				// Check for inline tag
				if ($this->isInline($sTag) && !$bClosed)
				{
					$sParsedBegin = $this->indent("<$sTag$sAttributes>", $iLevelM, false);
					$sParsed = $sContent;
					$sParsedEnd = "</$sTag>\n";
				}
				// Check for closed tag
				else
					$sParsedBegin = $this->indent("<$sTag$sAttributes />", $iLevelM);
			}
		}
		foreach ($this->aChildren as $oChild)
			$sParsed .= $oChild;
		// Check for IE comment
		if (preg_match('/^\\'.self::TOKEN_COMMENT.'\[(.*?)\](.*)/', $sSource, $aMatches))
		{
			$aMatches[2] = trim($aMatches[2]);
			if (count($this->aChildren) == 0)
			{
				$sParsedBegin = $this->indent("<!--[{$aMatches[1]}]> $sParsedBegin", 0, false);
				$sParsed = $aMatches[2];
				$sParsedEnd = "$sParsedEnd <![endif]-->\n";
			}
			else
			{
				$sParsed = $sParsedBegin.$sParsed.$sParsedEnd;
				$sParsedBegin = $this->indent("<!--[{$aMatches[1]}]>");
				$sParsedEnd = $this->indent("<![endif]-->");
			}
		} else
		// Check for comment
		if (preg_match('/^\\'.self::TOKEN_COMMENT.'(.*)/', $sSource, $aMatches))
		{
			$aMatches[1] = trim($aMatches[1]);
			if (count($this->aChildren) == 0)
			{
				$sParsedBegin = $this->indent("<!-- $sParsedBegin", 0, false);
				$sParsed = $aMatches[1];
				$sParsedEnd = "$sParsedEnd -->\n";
			}
			else
			{
				$sParsed = $sParsedBegin.$sParsed.$sParsedEnd;
				$sParsedBegin = $this->indent("<!--");
				$sParsedEnd = $this->indent("-->");
			}
		}
		$sCompiled = $sRealBegin.$sParsedBegin.$sParsed.$sParsedEnd.$sRealEnd;
		return $sCompiled;
	}
	
	/**
	 * Indent line
	 *
	 * @param string Line
	 * @param integer Additional indention level
	 * @param boolean Add new line
	 * @return string
	 */
	protected function indent($sLine, $iAdd = 0, $bNew = true)
	{
		$aLine = explode("\n", $sLine);
		$sIndented = '';
		foreach ($aLine as $sLine)
			$sIndented .= str_repeat('  ', $this->iIndent + $iAdd).($bNew ? "$sLine\n" : $sLine);
		return $sIndented;
	}
	
	/**
	 * Indent Haml source
	 * 
	 * @param string Source
	 * @param integer Level
	 * @return string
	 */
	protected function sourceIndent($sSource, $iLevel)
	{
		$aSource = explode(self::TOKEN_LINE, $sSource);
		foreach ($aSource as $sKey => $sValue)
			$aSource[$sKey] = str_repeat(self::TOKEN_INDENT, $iLevel * self::INDENT) . $sValue;
			$sSource = implode(self::TOKEN_LINE, $aSource);
			return $sSource;
	}
	
	/**
	 * Count level of line
	 *
	 * @param string Line
	 * @return integer
	 */
	protected function countLevel($sLine)
	{
		return (strlen($sLine) - strlen(trim($sLine, self::TOKEN_INDENT))) / self::INDENT;
	}
	
	/**
	 * Check for inline tag
	 * 
	 * @param string Tag
	 * @return boolean
	 */
	protected function isInline($sTag)
	{
		return in_array($sTag, self::$aInlineTags);
	}
	
	/**
	 * Check for closed tag
	 * 
	 * @param string Tag
	 * @return boolean
	 */
	protected function isClosed($sTag)
	{
		return in_array($sTag, self::$aClosedTags);
	}
	
	
	const TOKEN_LINE = "\n";//+
	const TOKEN_INDENT = ' ';//+
	const TOKEN_TAG = '%';//+
	const TOKEN_ID = '#';//+
	const TOKEN_CLASS = '.';//+
	const TOKEN_OPTIONS_LEFT = '{';//+
	const TOKEN_OPTIONS_RIGHT = '}';//+
	const TOKEN_OPTIONS_SEPARATOR = ',';//+
	const TOKEN_OPTION = ':'; //+
	const TOKEN_OPTION_VALUE = '=>';//+
	const TOKEN_INSTRUCTION_PHP = '-'; //+
	const TOKEN_PARSE_PHP = '=';//+
	const TOKEN_DOCTYPE = '!!!';//+
	const TOKEN_INCLUDE = '!!'; //+
	const TOKEN_COMMENT = '/'; //+
	const TOKEN_TRANSLATE = '$'; //+
	const TOKEN_LEVEL = '?'; //+
	const TOKEN_SINGLE = '/'; //+
	const TOKEN_BREAK = '|'; //+
	const TOKEN_AUTO_LEFT = '[';//+
	const TOKEN_AUTO_RIGHT = ']';//+
	const TOKEN_TEXT_BLOCKS = ':'; //+
	
	const INDENT = 2;//+
	
	/**
	 * Doctype definitions
	 *
	 * @var string
	 */
	protected static $aDoctypes = array
	(
		'1.1' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
		'Strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'Transitional' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'Frameset' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
		'XML' => "<?php echo '<?xml version=\"1.0\" encoding=\"utf-8\" ?>'; ?>\n"
	);
	
	/**
	 * List of inline tags
	 *
	 * @var array
	 */
	protected static $aInlineTags = array
	(
		'a', 'strong', 'b', 'em', 'i', 'h1', 'h2', 'h3', 'h4', 
		'h5', 'h6', 'span', 'title', 'li', 'dt', 'dd', 'code', 
		'cite', 'td', 'th', 'abbr', 'acronym', 'legend'
	);
	
	/**
	 * List of closed tags (like br, link...)
	 *
	 * @var array
	 */
	protected static $aClosedTags = array('br', 'hr', 'link', 'meta', 'img', 'input');

	/**
	 * List of PHP blocks
	 *
	 * @var array
	 *
	 */
	protected static $aPhpBlocks = array('if', 'else', 'elseif', 'while', 'switch', 'for', 'do');

	// Template engine

	/**
	 * Template variables
	 * 
	 * @var array
	 */
	protected static $aVariables = array();
	
	/**
	 * Assign variable
	 * 
	 * @param string Name
	 * @param mixed Value
	 * @return object
	 */
	public function assign($sName, $sValue)
	{
		self::$aVariables[$sName] = $sValue;
		return $this;
	}
	
	/**
	 * Assign associative array of variables
	 * 
	 * @param array Data
	 * @return object
	 */
	public function append($aData)
	{
		self::$aVariables = array_merge(self::$aVariables, $aData);
		return $this;
	}
	
	/**
	 * Removes variables
	 * 
	 * @return object
	 */
	public function clearVariables()
	{
		self::$aVariables = array();
		return $this;
	}
	
	/**
	 * Remove all compiled templates
	 * 
	 * @return object
	 */
	public function clearCompiled()
	{
		$oDirs = new DirectoryIterator($this->sTmp);
		foreach ($oDirs as $oDir)
			if (!$oDir->isDot())
				if (preg_match('/\.hphp/', $oDir->getPathname()))
				unlink($oDir->getPathname());
				return $this;
	}

	/**
	 * Return compiled template
	 * 
	 * @param string Filename
	 * @return string
	 */
	public function fetch($sFilename = false)
	{
		if ($sFilename)
			$this->setFile($sFilename);
		return $this->render();
	}
	
	/**
	 * Display template
	 * 
	 * @param string Filename
	 */
	public function display($sFilename = false)
	{
		echo $this->fetch($sFilename);
	}
	
	/**
	 * List of registered filters
	 * 
	 * @var array
	 */
	protected $aFilters = array();
	
	/**
	 * Register output filter
	 * 
	 * @param callable Filter
	 * @param string Name
	 * @return object
	 */
	public function registerFilter($mCallable, $sName = false)
	{
		if (!$sName)
			$sName = serialize($mCallable);
		$this->aFilters[$sName] = $mCallable;
		return $this;
	}
	
	/**
	 * Unregister output filter
	 * 
	 * @param string Name
	 * @return object
	 */
	public function unregisterFilter($sName)
	{
		unset($this->aFilters[$sName]);
		return $this;
	}
	
	/**
	 * Return array of template variables
	 * 
	 * @return array
	 */
	public function getVariables()
	{
		return self::$aVariables;
	}
	
	/**
	 * Parse variable in square brackets
	 *
	 * @param mixed Variable
	 * @return array Attributes
	 */
	protected function parseSquareBrackets($mVariable)
	{
		$sType = gettype($mVariable);
		$aAttr = array();
		$sId = '';
		if ($sType == 'object')
		{
			static $__objectNamesCache;
			if (!is_array($__objectNamesCache))
				$__objectNamesCache = array();
			$sClass = get_class($mVariable);
			if (!$__objectNamesCache[$sClass])
				$__objectNamesCache[$sClass] = $sType = trim(preg_replace('/([A-Z][a-z]*)/', '$1_', $sClass), '_');
			else
				$sType = $__objectNamesCache[$sClass];
			if (method_exists($mVariable, 'getID'))
				$sId = $mVariable->getID(); else
			if (!empty($mVariable->ID))
				$sId = $mVariable->ID;
		}
		if ($sId == '')
			$sId = substr(md5(uniqid(serialize($mVariable).rand(), true)), 0, 8);
		$aAttr['class'] = strtolower($sType);
		$aAttr['id'] = "{$aAttr['class']}_$sId";
		return $aAttr;
	}
	
	/**
	 * Write attributes
	 */
	protected function writeAttributes()
	{
		$aAttr = array();
		foreach (func_get_args() as $aArray)
			$aAttr = array_merge($aAttr, $aArray);
		foreach ($aAttr as $sName => $sValue)
			if ($sValue)
				echo " $sName=\"".htmlentities($sValue).'"';
	}
	
	/**
	 * Export array
	 * 
	 * @return string
	 */
	protected function arrayExport()
	{
		$sArray = 'array (';
		$aArray = $aNArray = array();
		foreach (func_get_args() as $aArg)
			$aArray = array_merge($aArray, $aArg);
		foreach ($aArray as $sKey => $sValue)
			$aNArray[] = "'$sKey' => $sValue";
		$sArray .= implode(', ', $aNArray).')';
		return $sArray;
	}
}

if (!function_exists('fake_translate'))
{
	/**
	 * Fake translation function used
	 * as default translation function
	 * in HamlParser
	 * 
	 * @param string
	 * @return string
	 */
	function fake_translate($s)
	{
		return $s;
	}
}

/**
 * This is the simpliest way to use
 * Haml templates. Global variables
 * are automatically assigned to
 * template.
 * 
 * @param string Haml parser filename
 * @param array Associative array of additional variables
 * @param string Temporary directory (default is directory of Haml templates)
 * @param boolean Register get, post, session, server and cookie variables
 */
function display_haml($sFilename, $aVariables = array(), $sTmp = true, $bGPSSC = false)
{
	global $__oHaml;
	$sPath = realpath($sFilename);
	if (!is_object($__oHaml))
		$__oHaml = new HamlParser(dirname($sPath), $sTmp);
	$__oHaml->append($GLOBALS);
	if ($bGPSSC)
	{
		$__oHaml->append($_GET);
		$__oHaml->append($_POST);
		$__oHaml->append($_SESSION);
		$__oHaml->append($_SERVER);
		$__oHaml->append($_COOKIE);
	}
	$__oHaml->append($aVariables);
	$__oHaml->display($sFilename);
}

?>