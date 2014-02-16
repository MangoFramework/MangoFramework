<?php

namespace utils\docgen;

Abstract Class Analysis Extends Builder
{
    protected $fullContent = array();

    private $builtArray = array();
    private $reflectionClass;

    // comming soon
    private function docPDF()
    {

    }

    private function docHTML()
    {
        $this->buildHtml($this->builtArray, $this->docPath);
    }

    private function sortByRange($a, $b)
    {
        return strcmp($a['infos']['range'], $b['infos']['range']);
    }

    private function buildArray()
    {
        foreach ($this->fullContent as $mainKey => $fullContent) {
            // using powerful reflection class
            $this->reflectionClass = new \ReflectionClass($fullContent['header']['fullClassName']);

            // infos
            if ($this->reflectionClass->isTrait()) {

            } else if ($this->reflectionClass->isInterface()) {

            } else {
                $this->builtArray[$mainKey]['infos']['shortClassName'] = $this->reflectionClass->getShortName();
                $this->builtArray[$mainKey]['infos']['longClassName'] = (!empty($fullContent['header']['longClassName'])) ? $fullContent['header']['longClassName'] : NULL;
                $this->builtArray[$mainKey]['infos']['classType'] = (($this->reflectionClass->isAbstract() === TRUE) ? 'abstract' : (($this->reflectionClass->isFinal() === TRUE) ? 'final' : 'simple'));
                if ($this->reflectionClass->getParentClass() !== FALSE) {
                    $this->builtArray[$mainKey]['infos']['parentClassName'] = substr($this->reflectionClass->getParentClass()->name, strrpos($this->reflectionClass->getParentClass()->name, '\\') + 1);
                }
                $this->builtArray[$mainKey]['infos']['isChild'] = (!empty($this->reflectionClass->getParentClass()->name)) ? TRUE : FALSE;
            }

            $this->builtArray[$mainKey]['infos']['namespace'] = ($this->reflectionClass->getNamespaceName()) ? $this->reflectionClass->getNamespaceName() : FALSE;
            $this->builtArray[$mainKey]['infos']['filePath'] = $this->reflectionClass->getFileName();
            $this->builtArray[$mainKey]['infos']['extension'] = substr($this->builtArray[$mainKey]['infos']['filePath'], strrpos($this->builtArray[$mainKey]['infos']['filePath'], '.') + 1);
            $this->builtArray[$mainKey]['infos']['fileName'] = substr($this->builtArray[$mainKey]['infos']['filePath'], strrpos($this->builtArray[$mainKey]['infos']['filePath'], '\\') + 1);
            $this->builtArray[$mainKey]['infos']['range'] = 'others';
            // analysis
            foreach ($fullContent as $secKey => $analysis) {
                if ($secKey === 'analysis') {
                    foreach ($analysis as $thiKey => $analys) {
                        foreach ($analys as $fouKey => $info) {
                            $info = trim($info);
                            if (preg_match('#^\s$#', $info) === 0) {
                                if (preg_match('#^(method|attribute|class)$#i', $info, $type)) {
                                    $property = $type[0];
                                }

                                if ($property != 'class') {
                                    if (preg_match('#^(((static)? ?(abstract|final)? ?(static)? ?(public|private|protected)? (abstract|final)? ?(static)?) ?(function)? +(.+))$#', $info, $properties)) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['visibility'] = (!empty($properties[6]) ? trim($properties[6]) : 'public');
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['isStatic'] = ($properties[5] === 'static' || $properties[3] === 'static') ? TRUE : FALSE;

                                        if (!empty($properties[3])) {
                                            $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['scope'] = trim($properties[4]);
                                        }

                                        if ($property === 'method') {
                                            if (strpos($properties[10], '{') && strpos($properties[10], '}')) {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = substr(trim($properties[10]), 0, -2);
                                            } else if (strpos($properties[10], '{')) {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = substr(trim($properties[10]), 0, -1);
                                            } else {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = trim($properties[10]);
                                            }
                                        }
                                    }

                                    if (preg_match('#^((@?type.+)((attribute) +(public|private|protected) +(static +)?(.*)))$#', $info, $attrType) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['visibility'] = trim($attrType[5]);
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['isStatic'] = (!empty($attrType[6]) && trim($attrType[6]) === 'static') ? TRUE : FALSE;
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['type'] = (trim($attrType[7] === 'Array()') ? strtolower(substr(trim($attrType[7]), 0, -2)) : trim($attrType[7]));
                                    }

                                    if (preg_match('#^((@?name)(.)?(.+))$#', $info, $name) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = trim($name[4]);
                                    }

                                    if (preg_match('#^@?param( ?: ?)?(([a-zA-Z]+) (\$[a-zA-Z0-9]+)(.+)?)$#', $info, $param) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['type'] = trim($param[3]);
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['name'] = trim($param[4]);
                                        if (!empty($param[5])) {
                                            $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['description'] = trim($param[5]);
                                        }
                                    }

                                    if (preg_match('#^@?return( ?: ?)?(([a-zA-Z]+) (\$[a-zA-Z0-9]+)(.+)?)$#', $info, $return) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['return'][$fouKey]['type'] = trim($return[3]);
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['return'][$fouKey]['name'] = trim($return[4]);
                                        if (!empty($return[5])) {
                                            $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['return'][$fouKey]['description'] = trim($return[5]);
                                        }
                                    }
                                }

                                if ($property == 'class') {
                                    if (preg_match('#^@?range( ?: ?)?([a-zA-Z]+)$#i', $info, $classRange) == TRUE) {
                                        $this->builtArray[$mainKey]['infos']['range'] = trim($classRange[2]);
                                    }
                                }

                                if (preg_match('#^@?type.+$#', $info, $methodType) === 1) {
                                    if ($analys[$fouKey - 1] === '') {
                                        $delimitDescrKey = $fouKey - 1;
                                    } else {
                                        $delimitDescrKey = $fouKey;
                                    }
                                }

                                if (!empty($delimitDescrKey)) {
                                    if ($property != 'class') {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['description'] = '';
                                    } else {
                                        $this->builtArray[$mainKey]['infos']['description'] = '';
                                    }
                                    for ($i = 3; $i < $delimitDescrKey; $i++) {
                                        if ($i === 3) {
                                            if ($property == 'class') {
                                                $this->builtArray[$mainKey]['infos']['description'] = $analys[$i];
                                            } else {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['description'] = $analys[$i];
                                            }
                                        } else {
                                            if ($property == 'class') {
                                                $this->builtArray[$mainKey]['infos']['description'] .= " " . $analys[$i];
                                            } else {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['description'] .= " " . $analys[$i];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function process()
    {
        if (!empty($this->fullContent)) {
            $this->buildArray();
            if (!empty($this->builtArray)) {
                usort($this->builtArray, array($this, 'sortByRange'));
                if (in_array($this->docType, ['html', 'pdf'])) {
                    $this->{'doc' . strtoupper($this->docType)}();
                }
            }
        }
    }
}

?>