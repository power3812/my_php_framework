<?php

class Uploader_File
{
    const UPLOAD_DIR_NAME = '/upload';

    protected $max_size           = 1;
    protected $allowed_extensions = [];
    protected $upload_dir_path    = '';

    public function __construct($dir = null)
    {
        $this->setDir($dir);
    }

    public function setDir($dir, $append = true)
    {
        if (is_empty($dir)) {
            $dir = PROJECT_ROOT . '/' . self::UPLOAD_DIR_NAME;
        } else {
            $dir = PROJECT_ROOT . '/' . ltrim($dir, '/');
        }

        if (file_exists($dir) && is_file($dir)) {
            throw new LogicException(__METHOD__ . '() ' . $dir . ' is a file.');
        }

        if (!file_exists($dir)) {
            if ($append) {
                if (!mkdir($dir, 0777, true)) {
                    throw new LogicException(__METHOD__ . '() Failed to create directory ' . $dir . '.');
                }
            } else {
                throw new LogicException(__METHOD__ . '() Directory not found. ' . $dir);
            }
        }

        $this->upload_dir_path = $dir;
    }

    public function setAllowedExtensions($exts)
    {
        if (!is_array($exts)) {
            $exts = (array) $exts;
        }

        $this->allowed_extensions = array_map('strtolower', $exts);
    }

    public function getAllowedExtensions()
    {
        return $this->allowed_extensions;
    }

    public function setMaxSize($max_size)
    {
        $this->max_size = $max_size;
    }

    public function getMaxSize()
    {
        return $this->max_size;
    }

    public function isExtensionValid($file_name)
    {
        if (is_empty($this->allowed_extensions)) {
            return true;
        } else {
            return (in_array($this->getExtension($file_name), $this->allowed_extensions));
        }
    }

    public function isSizeValid($size)
    {
        return ($size <= ($this->max_size * 1048576));
    }

    public function generateFileName($ext = null)
    {
        $name = generate_random_string();

        if (!is_empty($ext)) {
            $name .= '.' . $ext;
        }

        if (file_exists($this->upload_dir_path . '/' . $name)) {
            $name = $this->generateFileName($ext);
        }

        return $name;
    }

    public function getExtension($file_name, $to_lower_case = true)
    {
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        return ($to_lower_case) ? strtolower($ext) : $ext;
    }

    public function upload($data, $file_name)
    {
        $file_path = $this->upload_dir_path . '/' . $file_name;
        if (!file_put_contents($file_path, $data)) {
            throw new RuntimeException(__METHOD__ . '() Failed to upload a file. ' . $file_path);
        }

        chmod($file_path, 0777);

        return true;
    }

    public function delete($file_name, $move = false)
    {
        if ($move) {
            $src_path = $this->upload_dir_path . '/' . $file_name;
            $dst_dir  = dirname($this->upload_dir_path) . '/' . basename($this->upload_dir_path) . '_deleted';
            $dst_path = $dst_dir . '/' . $file_name;

            if (!is_dir($dst_dir)) {
                if (!mkdir($dst_dir, 0777, true)) {
                    throw new LogicException(__METHOD__ . '() Failed to create directory ' . $dst_dir . '.');
                }
            }

            if (!rename($src_path, $dst_path)) {
                throw new LogicException(__METHOD__ . '() Failed to move a file. ' . $src_path . ' -> ' . $dst_dir);
            }
        } else {
            $file_path = $this->upload_dir_path . '/' . $file_name;

            if (file_exists($file_path)) {
                if (!unlink($file_path)) {
                    throw new RuntimeException(__METHOD__ . '() Failed to delete a file. ' . $file_path);
                }
            }
        }

        return true;
    }
}
