<?php
/**
 * @Idea: Lop xu ly XML
 *	Ngay tao: 09/01/2009
 */
class RAX {
	public function RAX () {
		$this->record_delim = '';		
		$this->fields = array();
		$this->records = array();
		$this->parser='';
		$this->in_rec = 0;
		$this->in_field = 0;
		$this->field_data = '';
		$this->tag_stack = array();
		$this->xml = '';
		$this->xml_file='';
		$this->rax_opened = 0;
		$this->debug = 0;
		$this->version = '0.1';
		$this->parse_done='';
		$this->parse_started ='';

	}
	
	public function open ($xml) {
		$this->debug("open(\"$xml\")");
		if ($this->rax_opened) return 0;
		$this->xml = $xml;
		$this->rax_opened = 1;
	}
	
	public function openfile ($filename) {
		$this->debug("openfile(\"$filename\")");
		if ($this->rax_opened) return 0;
		$fp = fopen($filename, "r");
		if ($fp) {
			$this->xml_file = $fp;
			$this->rax_opened = 1;
			return 1;
		}
		return 0;
	}
	
	public function startparse () {
		$this->debug("startparse()");
		$this->parser = xml_parser_create();
		xml_set_object($this->parser,$this);
		xml_set_element_handler($this->parser,  "startElement",  "endElement");
		xml_set_character_data_handler($this->parser,  "characterData");
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		if (xml_parse($this->parser, '')) {
			$this->parse_started = 1;
			return 1;
		}
		return 0;
	}
	
	public function parse () {
		$this->debug("parse()");
		if (!$this->rax_opened) return 0;
		if ($this->parse_done) return 0;
		if (!$this->parse_started) 
			if (!$this->startparse()) return 0;
		if ($this->xml_file) {
			$buffer = fread($this->xml_file, 4096);
			if ( $buffer )
				xml_parse( $this->parser, $buffer, feof($this->xml_file) );
			else {
				$this->parse_done = 1;
			}
		}
		else {
			xml_parse($this->parser, $this->xml, 1);
			$this->parse_done = 1;
		}
		return 1;
	}
	
	public function startElement($parser, $name, $attrs) {
		$this->debug("startElement($name)");
		array_push($this->tag_stack, $name);
		if ( !$this->in_rec and !strcmp($name, $this->record_delim) ) {
			$this->in_rec = 1;
			$this->rec_lvl = sizeof($this->tag_stack);
			$this->field_lvl = $this->rec_lvl + 1;
		}
		else if ( $this->in_rec and sizeof($this->tag_stack) == $this->field_lvl ) {
			$this->in_field = 1;
		}
	}
	
	public function endElement($parser, $name) {
		$this->debug("endElement($name)");
		array_pop($this->tag_stack);
		if ( $this->in_rec ) {
			if ( sizeof($this->tag_stack) < $this->rec_lvl ) {
				$this->in_rec = 0;
				array_push( $this->records, new RAX_Record( $this->fields ) );
				$this->fields = array();
			}
			else if ( sizeof($this->tag_stack) < $this->field_lvl ) {
				$this->in_field = 0;
				$this->fields[$name] = $this->field_data;
				$this->field_data = '';
			}
		}
	}
	
	public function characterData ($parser, $data) {
		$this->debug("characterData($data)");
		if ( $this->in_field ) 
			$this->field_data .= $data;
	}
	
	public function setRecord ($delim) {
		$this->debug("setRecord($delim)");
		if ($this->parse_started) return 0;
		$this->record_delim = $delim;
		return 1;
	}
	
	function readRecord () {
		$this->debug("readRecord()");
		while ( !sizeof($this->records) and !$this->parse_done ) $this->parse();
		return array_shift($this->records);
	}
	
	public function debug ($msg) {
		if ($this->debug) print "$msg<br />\n";
	}
	
	// added by Wouter Demuynck
	public function close() {
		if ($this->rax_opened) {
			$this->debug("Closing RAX");
			fclose($this->xml_file);
			$this->xml_file = 0;
			$this->rax_opened = 0;
		}
	}
}

class RAX_Record {
	function RAX_Record ( $fields ) {
		$this->fields = $fields;
		$this->debug = 0;
	}
	function getFieldnames () {
		$this->debug("getFieldnames()");
		return array_keys( $this->fields );
	}
	function getField ( $field ) {
		$this->debug("getField($field)");
		return trim( $this->fields[$field] );
	}
	function getFields () {		
		$this->debug("getFields()");
		return array_values( $this->fields );
	}
	function getRow () {
		$this->debug("getFields()");
		return $this->fields;
	}
	function debug ($msg) {
		if ($this->debug) print "$msg<br />\n";
	}
}