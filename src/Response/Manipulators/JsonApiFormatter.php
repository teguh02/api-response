<?php

namespace teguh02\ApiResponse\Response\Manipulators;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class JsonApiFormatter 
{
    public static $RULES = ['key', 'action', 'value'];
    public static $ACTION = ['replace', 'append', 'prepend', 'callback'];

    public static function make(array $data, string $formatterClass) 
    {
        foreach (app($formatterClass)->format() as $value_rule) {
            // Check if value_rule keys are does not exists in the rules
            self::validate_rules(array_keys($value_rule));

            foreach ($data as $k => $v) {
                // Check if user try to modify array data inside the array
                if (Str::contains($value_rule['key'], '.')) {
                    $array_key = Str::before($value_rule['key'], '.');
                    $array_target = Str::after($value_rule['key'], '.');

                    foreach ($data[$k][$array_key] as $child_key => $child_value) {
                        $data[$k][$array_key][$child_key][$array_target] = self::apply_rules(
                            data: $data[$k][$array_key][$child_key][$array_target], 
                            rules: $value_rule
                        );
                    }

                // Check if user try to non array data
                } else {
                    $data[$k][$value_rule['key']] = self::apply_rules(
                        data: $v[$value_rule['key']], 
                        rules: $value_rule
                    );
                }
            }
        }

        return $data;
    }

    private static function validate_rules(array $rule_keys) : void
    {
        foreach (self::$RULES as $value) {
            // Check if value was not found in the rules keys
            // then throw rules is missing
            if (!in_array($value, $rule_keys)) {
                throw new \Exception("Rules is missing: {$value}");
            }
        }
    }

    private static function apply_rules(mixed $data, array $rules) {
        // Check if given action is not in the action rules
        if (!in_array(Str::before($rules['action'], '['), self::$ACTION)) {
            throw new \Exception("Action is not valid: {$rules['action']}");
        }

        return match (Str::before($rules['action'], '[')) {
            'replace' => self::replace_action($data, Str::betweenFirst($rules['action'], '[find:', ']'), $rules['value']),
            'append' => Str::of($data)->append($rules['value']),
            'prepend' => Str::of($data)->prepend($rules['value']),
            'callback' => is_callable($rules['value']) ? call_user_func($rules['value'], $data) : $data,
        };
    }

    private static function replace_action(string $data, string $find, string $new_value) {
        if (Str::contains($find, ',')) {
            $find_explode = explode(',', $find);

            foreach ($find_explode as $value) {
                $data = Str::replaceFirst($value, $new_value, $data);
            }
        }

        return Str::replaceFirst($find, $new_value, $data);
    }
}