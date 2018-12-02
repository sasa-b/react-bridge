<?php

use function ReactBridge\str_has;
use function ReactBridge\js_code;
use function ReactBridge\html;
use function ReactBridge\is_prod;
use function ReactBridge\env;
use const ReactBridge\BOOT_CODE;

/**
 * @param string|array $id
 * @param array $data
 * @param array $options
 * @return string
 */
function react_component($id, array $data = [], array $options = []): string
{
    if (is_array($id)) list($id, $component) = $id;

    if (is_prod()) return html($id, $data);

    $opts = array_merge([
        'ref' => false,
        'component' => $component ?? null,
        'path' => env('REACT_BRIDGE_PATH', dirname(__DIR__, 4).'/resources/assets/js'),
        'folder' => env('REACT_BRIDGE_FOLDER', 'components'),
        'filename' => env('REACT_BRIDGE_FILENAME', 'react')
    ], $options);

    $writeTo = $opts['path']."/".$opts['filename'].".js";

    if (file_exists($writeTo)) {
        $file = file_get_contents($writeTo);

        [$comment, $import, $code] = js_code($id, $opts['component'], $opts['ref'], $opts['folder']);

        if (!str_has($file, $id) && !str_has($file, $code)) {
            $write = str_has($file, $import) ? "{$comment}{$code}" : "{$comment}{$import}{$code}";
            file_put_contents($writeTo, $write, FILE_APPEND);
        }

        return html($id, $data);
    }

    file_put_contents($writeTo, BOOT_CODE.implode(js_code($id, $opts['component'], $opts['ref'], $opts['folder'])));

    return html($id, $data);
}

/**
 * @alias react_component
 * @param string|array $id
 * @param array $data
 * @param array $options
 * @return string
 */
function rc($id, array $data = [], array $options = []): string
{
    return react_component($id, $data, $options);
}
