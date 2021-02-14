<?php

class Validator
{
    protected $input_rules;

    public function __construct(array $input_rules)
    {
        $this->input_rules = $input_rules;
    }

    public function validate(array $inputs)
    {
        $errors = [];

        foreach ($this->input_rules as $input_name => $input_rule) {
            if (isset($input_rule['required']) && array_key_exists($input_name, $inputs)) {
                $error = $this->required($inputs[$input_name], $input_rule['required'], $input_rule['name']);

                if (!is_empty($error)) {
                    $errors[] = $error;
                }
            }

            if (isset($input_rule['required_word']) && array_key_exists($input_name, $inputs)) {
                $error = $this->requiredWord($inputs[$input_name], $input_rule['required_word'], $input_rule['name']);

                if (!is_empty($error)) {
                    $errors[] = $error;
                }
            }

            if (isset($input_rule['digit']) && array_key_exists($input_name, $inputs)) {
                $error = $this->digit($inputs[$input_name], $input_rule['digit'], $input_rule['name']);

                if (!is_empty($error)) {
                    $errors[] = $error;
                }
            }

            if (isset($input_rule['length']) && array_key_exists($input_name, $inputs)) {
                $error = $this->length($inputs[$input_name], $input_rule['length'], $input_rule['name']);

                if (!is_empty($error)) {
                    $errors[] = $error;
                }
            }

            if (isset($input_rule['extension']) && array_key_exists($input_name, $inputs)) {
                $error = $this->extension($inputs[$input_name], $input_rule['extension'], $input_rule['name']);

                if (!is_empty($error)) {
                    $errors[] = $error;
                }
            }

            if (isset($input_rule['file_size']) && array_key_exists($input_name, $inputs)) {
                $display_unit = null;

                if (isset($input_rule['display_unit'])) {
                    $display_unit = $input_rule['display_unit'];
                }

                $error = $this->fileSize($inputs[$input_name], $input_rule['file_size'], $input_rule['name'], $display_unit);

                if (!is_empty($error)) {
                    $errors[] = $error;
                }
            }
        }

        return $errors;
    }

    protected function required(?string $input, bool $required, string $name)
    {
        if (!is_bool($required)) {
            throw new LogicException('requiredはbool型でなくてはなりません。');
        }

        $error = null;

        if ($required) {
            if (is_empty($input)) {
                $error = $name . 'を入力して下さい。';
            }
        }

        return $error;
    }

    protected function requiredWord(?string $input, string $required_word, string $name)
    {
        if (!is_string($required_word)) {
            throw new LogicException('requiredWordはbool型でなくてはなりません。');
        }

        $error = null;

        if (!mb_strpos($input, $required_word)) {
            $error = $name . 'には' . $required_word . 'を含めて下さい。';
        }

        return $error;
    }

    protected function digit(?string $input, ?bool $digit, string $name)
    {
        if (!is_bool($digit)) {
            throw new LogicException('digitはbool型でなくてはなりません。');
        }

        $error = null;

        if (is_empty($input)) {
            return $error;
        }

        if ($digit) {
            if (!ctype_digit((string) $input)) {
                $error = $name . 'は数字のみで入力して下さい。';
            }
        }

        return $error;
    }

    protected function length(?string $input, array $length, string $name)
    {
        if (!isset($length['max']) && !isset($length['min']) && !isset($length['number'])) {
            throw new LogicException('lengthにはmax、min、numberのいづれかがセットされていなければなりません。');
        }

        $error = null;

        if (is_empty($input)) {
            return $error;
        }

        $input_value_length = mb_strlen($input);
        $unit               = '文字';

        if (isset($length['unit'])) {
            $unit = $length['unit'];
        }

        if (isset($length['min']) && isset($length['max'])) {
            if (!is_int($length['min']) || !is_int($length['max'])) {
                throw new LogicException('max、minはint型でなくてはなりません。');
            }

            if (($input_value_length < $length['min']) || ($input_value_length > $length['max'])) {
                $error = $name . 'は' . $length['min'] . $unit . '以上' . $length['max'] . $unit . '以下にしてください。';
            }
        } elseif (isset($length['min'])) {
            if (!is_int($length['min'])) {
                throw new LogicException('minはint型でなくてはなりません。');
            }

            if ($input_value_length < $length['min']) {
                $error = $name . 'は' . $length['min'] . $unit . '以上にしてください。';
            }
        } elseif (isset($length['max'])) {
            if (!is_int($length['max'])) {
                throw new LogicException('maxはint型でなくてはなりません。');
            }

            if ($input_value_length > $length['max']) {
                $error = $name . 'は' . $length['max'] . $unit . '以下にしてください。';
            }
        } elseif (isset($length['number'])) {
            if (!is_int($length['number'])) {
                throw new LogicException('numberはint型でなくてはなりません。');
            }

            if ($input_value_length !== $length['number']) {
                $error = $name . 'は' . $length['number'] . $unit . 'にしてください。';
            }
        }

        return $error;
    }

    protected function extension(?string $input, array $extensions, string $name)
    {
        $error      = null;
        $mime_types = [
            'txt'  => 'text/plain',
            'html' => 'text/html',
            'gif'  => 'image/gif',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'ico'  => 'image/vnd.microsoft.icon',
            'mpg'  => 'video/mpeg',
            'mpeg' => 'video/mpeg',
            'mp4'  => 'video/mp4',
            'mp3'  => 'audio/mpeg',
            'wav'  => 'audio/wav',
            'zip'  => 'application/zip',
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'xls'  => 'application/msexcel',
        ];

        if (is_empty($input)) {
            return $error;
        }

        if (!file_exists($input)) {
            throw new LogicException('ファイルが存在しません。');
        }

        $input_mime_type = mime_content_type($input);

        if (!$input_mime_type) {
            throw new RuntimeException('ファイルのmime-typeが取得できません。');
        }

        $is_match_mime_type = false;

        foreach ($extensions as $extension) {
            if (!isset($mime_types[$extension])) {
                throw new LogicException('サポートしていない拡張子です。');
            }

            if ($input_mime_type === $mime_types[$extension]) {
                $is_match_mime_type = true;
            }
        }

        if (!$is_match_mime_type) {
            $error = $name . 'の拡張子は' . implode('、', $extensions) . 'でなければいけません。';
        }

        return $error;
    }

    protected function fileSize(?string $input, array $file_size, string $name, string $display_unit = null)
    {
        if (!isset($file_size['max']) && !isset($file_size['min'])) {
            throw new LogicException('file_sizeにはmax、minのいづれかがセットされていなければなりません。');
        }

        $error = null;

        if (is_empty($input)) {
            return $error;
        }

        if (!filesize($input)) {
            throw new LogicException('ファイルの容量を取得できません。');
        }

        $input_file_size    = filesize($input);
        $display_units      = ['B', 'KB', 'MB', 'GB', 'TB'];
        $display_input_unit = 'B';

        if ($display_unit !== null) {
            if (in_array($display_unit, $display_units)) {
                $display_input_unit = $display_unit;
            } else {
                throw new LogicException('サポートしていないdisplay_unitです。');
            }
        }

        $display_file_size = [];

        if (isset($file_size['max'])) {
            if (!is_int($file_size['max'])) {
                throw new LogicException('maxはint型でなくてはなりません。');
            }

            $display_file_size['max'] = $file_size['max'];
        }

        if (isset($file_size['min'])) {
            if (!is_int($file_size['min'])) {
                throw new LogicException('minはint型でなくてはなりません。');
            }

            $display_file_size['min'] = $file_size['min'];
        }

        foreach ($display_units as $display_unit) {
            if ($display_input_unit === $display_unit) {
                break;
            }

            if (isset($file_size['max'])) {
                $display_file_size['max'] = (int) ceil($display_file_size['max'] / 1024);
            }

            if (isset($file_size['min'])) {
                $display_file_size['min'] = (int) ceil($display_file_size['min'] / 1024);
            }
        }

        if (isset($file_size['min']) && isset($file_size['max'])) {
            if (($input_file_size < $file_size['min']) || ($input_file_size > $file_size['max'])) {
                $error = $name . 'は' . $display_file_size['min'] . $display_input_unit . '以上'
                    . $display_file_size['max'] . $display_input_unit . '以下にしてください。';
            }
        } elseif (isset($file_size['min'])) {
            if ($input_file_size  < $file_size['min']) {
                $error = $name . 'は' . $display_file_size['min'] . $display_input_unit . '以上にしてください。';
            }
        } elseif (isset($file_size['max'])) {
            if ($input_file_size > $file_size['max']) {
                $error = $name . 'は' . $display_file_size['max'] . $display_input_unit . '以下にしてください。';
            }
        }

        return $error;
    }
}
