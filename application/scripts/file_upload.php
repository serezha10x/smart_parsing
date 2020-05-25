<?php

use application\util\FileVerification;

require_once $_SERVER["DOCUMENT_ROOT"] . "/application/util/FileVerification.php";

$json = array();

if (isset($_POST['submit'])) {
        if (!empty($_FILES['upload_file'])) {
            $file_name = $_FILES['upload_file']['name'];
            if (!isAvailableFileName(array("1_Драгун.doc"), $file_name)) {
                if (isAvailableFileFormat($file_name)) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/docs/";
                    $path .= basename($_FILES['upload_file']['name']);
                    if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $path)) {
                        setcookie('upload_file', $path, time() + 3600, "/");
                        $json += ['message' => 'Файл загружен!'];
                        //echo "Файл загружен!";
                        header('Location: http://nirs.com/parse');
                        exit;
                    } else {
                        if (isset($_COOKIE['upload_file'])) {
                            setcookie("upload_file", $path, -1);
                        }
                    }
                } else {
                    $json += ['message' => 'false format'];
                    //echo "false format";
                }
            } else {
                $json += ['message' => 'false name'];
               // echo "false name";
            }
        }
    }

    //echo json_encode($json);


    function isAvailableFileName($array_file_name, $upload_file_name) : bool {
        foreach ($array_file_name as $file_name) {
            if ($file_name === $upload_file_name) {
                return true;
            } else {
                return false;
            }
        }
    }

    function isAvailableFileFormat($upload_file_name) {
        $fileArray = pathinfo($upload_file_name);
        $file_ext = $fileArray['extension'];

        if (!FileVerification::CheckFormat($file_ext)) {
            return false;
        } else {
            return true;
        }
    }