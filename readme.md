## React Bridge

A small library that helps you make hybrid apps by integrating React.js into full stack PHP frameworks.

Inspired by __react-rails__ gem

### Getting started

All you need to do to integrate React into your favorite full stack MVC project is:

1. `npm install react` or `yarn add react`

2. `composer require sasab/react-bridge`

3. Call the `react_component` or `rc` function somewhere in your views

4. Finally, add the generated file(s) to your bundle or directly into your html via the script tag

If you are a __Laravel__ user you can start using it out of the box, it uses Laravel's folder structure by default.

The "bundling" `react.js` file will be generated in `/resources/assets/js` and it will look for your React component files 
in the `/resources/assets/js/components` folder by default.

This function will detect the environment of your app from the `.env` file, if it is in _production_ it will just
return the `<div id='my-component' data-props='{"foo":"bar"}'>` file required by React. If it is in development it will write/append to the `react.js` file.

You can override the defaults by either using the `.env` file or providing the overrides as the third param to the `react_component` function. 

```php

    // Data provided from the server that will be passed as props to your component
    $props = ['foo' => 'bar'];

    // Default options
    <?= react_component('my-component', $props, [
        'ref' => false,
        'component' => null,
        'path' => '/resources/assets/js',
        'folder' => 'components',
        'filename' => 'react'    
    ]) ?>
    
    // or in Laravel's blade

    {!! react_component('my-laravel-component', ['foo' => 'bar']) !!}
```

.env:
```
REACT_BRIDGE_PATH=
REACT_BRIDGE_FOLDER=
REACT_BRIDGE_FILENAME=
```


### Options

1. __ref__ - if you set it to `true` your component will have have a reference on the global
window object `window._components.MyComponent`
2. __component__ - by default the first parameter to the `react_component` function represents the html `id` attribute which will be turned into the `StudlyCase` name of your component, 
and the function will look into the root of the `../components/` folder for your actual _React.jsx_ component file. 

    If you want to provide a custom name or a custom path or both you can provide it as this parameter 
e.g `react('my-component', $data, ['component' => 'Articles/Comments/SingleComment'])`. 

    Dot-notation is also supported so you can write it like this as well `react('my-component', $data, ['component' => 'Articles.Comments.SingleComment'])`.
3. __path__ - base path where your bundling file(s) will be generated

4. __folder__ - folder name inside the __path__ where your React.jsx components will be located

5. __filename__ - name of your bundling file(s)    