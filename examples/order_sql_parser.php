<?php
namespace PHPSQLParser;
require_once dirname(__FILE__) . '/../vendor/autoload.php';

function dump($array) {
  echo "<pre>";
  var_dump($array);
  echo "</pre>";
}

$handle = @fopen("./order_sql.txt", "r");
if ($handle) {
    $parser_array = array();
    while (($buffer = fgets($handle, 4096)) !== false) {
        $parser_string = "";
        $start = microtime(true);
        $parser = new PHPSQLParser($buffer, true);
        $stop = microtime(true);
        foreach ($parser->parsed as $key => $value) {
            $parser_string .= $key . " ";
            if (is_array($value)) {
                foreach ($value as $key_2 => $value_2) {
                    switch ($value_2['expr_type']) {
                        case 'reserved':
                        $parser_string .= $value_2['base_expr'] . " ";
                        break;
                        case 'table':
                        $parser_string .= $value_2['table'] . " ";
                        break;
                        case 'column-list':
                        $parser_string .= $value_2['base_expr'] . " ";
                        break;
                        /*case 'record':
                        foreach ($value_2['data'] as $data_key => $value) {
                            # code...
                        }
                        $parser_string .= $value_2['base_expr'] . " ";
                        break;*/

                        case 'expression':
                        if (is_array($value_2['sub_tree'])) {
                            foreach ($value_2['sub_tree'] as $key_3 => $sub_tree) {
                                switch ($sub_tree['expr_type']) {
                                    case 'colref':
                                    $parser_string .= $sub_tree['base_expr'] . " ";
                                    break;
                                    case 'operator':
                                    $parser_string .= $sub_tree['base_expr'] . " ";
                                    break;
                                    case 'const':
                                    $parser_string .= "? ";
                                    break;
                                }
                            }
                        }
                        break;
                        case 'bracket_expression':
                        if (is_array($value_2['sub_tree'])) {
                            foreach ($value_2['sub_tree'] as $key_3 => $sub_tree) {
                                switch ($sub_tree['expr_type']) {
                                    case 'colref':
                                    $parser_string .= $sub_tree['base_expr'] . " ";
                                    break;
                                    case 'operator':
                                    $parser_string .= $sub_tree['base_expr'] . " ";
                                    break;
                                    case 'const':
                                    $parser_string .= "? ";
                                    break;
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }
        $parser_array[] = $parser_string;
        // dump($parser->parsed);
    }
    dump(array_unique($parser_array));
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}