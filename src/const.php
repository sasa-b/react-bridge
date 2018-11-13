<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11/13/18
 * Time: 8:37 PM
 */
namespace ReactBridge;

const ID = "\n//%id%\n";

const IMPORT = "import %component% from '%file_path%;\n";

const REF = "        props.ref = (ref) => { window._components.%component%Component = ref; };\n";

const CODE =
"const %element% = document.getElementById('%id%');\n
if (%element%) {
    let props = %element%.dataset.props ? JSON.parse(%element%.dataset.props) : {};\n%ref%
    ReactDOM.render(React.createElement(%component%, props, null), %element%);
};\n";

const BOOT_CODE = "
import React from 'react';
import ReactDOM from 'react-dom';

window._components = window._components ? window._components : {};\n";

