<?php
namespace JBus\JSBuilder;

class FunctionList
{
    const AT='at';
    const DOM='dom';
    const STATEFUL='Stateful';
    const FILTERING_SELECT='FilteringSelect';
    const MEMORY_LEGACY='MemoryLegacy';
    const READY='ready';
    const BUTTON='Button';   
    const REQUEST_JBUS='requestJbus';
    const COMPONENT='Component';
    const EMPTY_COMP='Empty';
    const TEXT='TextBox';
    const TEXT_AREA='Textarea';
    const SPINNER='Spinner';
    const GRID='Grid';
    const PASSWORD='Password';
    const STORE='Store';
    const Deferred='Deferred';
    const TOOLTIP_JBUS='tooltip';
    const DIALOG='dialog';
    const WINDOW='window';
    const LABEL='label';
    const IMAGE='image';
    const TOOL='tool';
    const BORDER_CONTAINER='BorderContainer';
    const TAB_CONTAINER='TabContainer';
    const TIME='Time';
    const DATE='Date';
	const UPLOADER='Uploader';
    const UPLOADER_FILE_LIST='UploaderFileList';
    const CHECKBOX='CheckBox';

    public static $functSet=array(
            self::AT=>'dojox/mvc/at',
            self::DOM=>'dojo/dom',
            self::STATEFUL=>"dojo/Stateful",
            self::FILTERING_SELECT=>'ext/jbus/FilteringSelect',
            self::MEMORY_LEGACY=>'dojo/store/Memory',
    		self::TIME=>'dijit/form/TimeTextBox',
            //self::MEMORY=>"dstore/Memory",
            self::READY=>"dojo/domReady!",
    		self::PASSWORD=>"ext/jbus/Password",
            self::BUTTON=>"dijit/form/Button",
            self::REQUEST_JBUS=>"ext/requestJbus",
            self::COMPONENT=>"ext/jbus/Component",
            self::TEXT=>"dijit/form/ValidationTextBox",
            self::TEXT_AREA=>"dijit/form/Textarea",
            self::SPINNER=>"dijit/form/NumberSpinner",
            self::GRID=>"ext/jbus/Grid",
            self::STORE=>"ext/jbus/Store",
            self::Deferred=>"dojo/Deferred",
            self::TOOLTIP_JBUS=>"ext/jbus/tooltip",
            self::DIALOG=>"ext/jbus/Dialog",
            self::WINDOW=>"ext/jbus/Window",
            self::LABEL=>"ext/jbus/Label",
            self::IMAGE=>"ext/jbus/Image",
            self::TOOL=>"ext/Tool",
            self::BORDER_CONTAINER=>'dijit/layout/BorderContainer',
			self::DATE=>"dijit/form/DateTextBox",
            self::TAB_CONTAINER=>"dijit/layout/TabContainer",
            self::EMPTY_COMP=>"ext/jbus/Empty",
    		self::CHECKBOX=>'dijit/form/CheckBox',
			self::UPLOADER=>"ext/jbus/Uploader",
            self::UPLOADER_FILE_LIST=>'dojox/form/uploader/FileList'
    );
}