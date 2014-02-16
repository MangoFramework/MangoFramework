<?php

namespace utils\docgen;

Abstract Class Builder
{
    private $html;
    private $css;
    private $script;

    private function createDir()
    {

    }

    private function buildPDF($builtArray)
    {

    }

    protected function buildHtml($analysis = array(), $docPath)
    {
        $builtArray = var_export($analysis, TRUE);

        $docHtmlContent = <<<EOT
<?php

    \$builtArrayInject = $builtArray;

    \$menuNames = array();

    foreach (\$builtArrayInject as \$fileParsed) {
        \$menuName = \$fileParsed['infos']['range'];
        \$menuNames[\$menuName][] = \$fileParsed['infos']['shortClassName'];
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <meta name="application-name" content="MangoDocGen">
        <meta name="description" content="Project Documentation">
        <meta name="Date" content="<?php echo date("D, j M Y G:i:s"); ?>">
        <title>Mango Documentation Generator</title>

        <style>
            section
            {
                margin-left: 5px;
                margin-bottom: 5px;
                padding: 0 5px 5px 5px;
            }

            section
            {
                float: left;
            }

            header h3,
            section article h4
            {
                text-align: center;
            }

            section h3
            {
                border-bottom: 2px solid Black;
            }

            nav
            {
                float: left;

                padding: 5px;
                border-right: 2px solid black;
            }

            footer
            {
                clear: both;
            }

            li
            {
                //list-style-type: none;
            }

            div.files
            {
                border-bottom: 1px solid black;
            }
        </style>
    </head>

    <body>
        <header>
            <h3>Mango Documentation Generator</h3>
        </header>

        <nav>
            <?php

                foreach(\$menuNames as \$mainKey => \$names) {
                    echo ('<a href="#' . \$mainKey . '">' . ucfirst(\$mainKey) . '</a>');
                    echo ('<ul>');

                    foreach (\$names as \$key => \$name) {

           ?>

                        <li>
                            <a href="#<?php echo strtolower(\$name); ?>">
                                <?php echo ('<span>' . \$name . '</span>'); ?>
                            </a>
                        </li>

           <?php

                        if (count(\$menuNames[\$mainKey]) - 1 === \$key) {
                            echo ('</ul>');
                        }

                    }
                }

            ?>
        </nav>

        <section>
            <?php

                \$sectionNamesStart = array();
                \$sectionNamesEnd = array();

                foreach (\$builtArrayInject as \$mainKey => \$fileParsed) {
                    foreach (\$menuNames as \$menuKey => \$name) {
                        if (!in_array(\$menuKey, \$sectionNamesStart)) {
                            if (\$menuKey === \$fileParsed['infos']['range']) {
                                \$sectionNamesStart[] = \$menuKey;
                                echo ('<h3 id="' . \$menuKey . '">' . ucfirst(\$menuKey) . '</h3>');
                            }
                        }
                    }

            ?>

                    <div class="files" id="<?php echo strtolower(\$fileParsed['infos']['shortClassName']); ?>">
                        <h4><?php echo \$fileParsed['infos']['shortClassName']; ?></h4>

                        <div>
                            <?php

                                if (!empty(\$fileParsed['infos']['description'])) {
                                    echo ('<p>' . \$fileParsed['infos']['description'] . '</p>');
                                }

                            ?>

                            <ul>
                                <li>
                                    <span>Namespace: </span>
                                    <?php

                                        if (!empty(\$fileParsed['infos']['namespace'])) {
                                            echo \$fileParsed['infos']['namespace'];
                                        } else {
                                            echo 'none';
                                        }

                                    ?>
                                </li>
                                <li>
                                    <span>Class type: </span>
                                    <?php

                                        if (!empty(\$fileParsed['infos']['classType'])) {
                                            echo \$fileParsed['infos']['classType'];
                                        } else {
                                            echo 'simple';
                                        }

                                    ?>
                                </li>
                                <li>
                                    <span>Parent class:</span>
                                    <?php

                                        if (\$fileParsed['infos']['isChild'] === TRUE) {
                                            echo \$fileParsed['infos']['parentClassName'];
                                        } else {
                                            echo 'none';
                                        }

                                    ?>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <?php

                                echo ('<div>');
                                if (!empty(\$fileParsed['analysis']['attribute'])) {
                                    echo ('<span>Attributes: </span> <ul>');
                                    foreach (\$fileParsed['analysis']['attribute'] as \$attribute) {

                            ?>

                                    <li>
                                        <?php echo \$attribute['name']; ?>
                                        <ul>
                                            <li>
                                                Description: <?php echo (!empty(\$attribute['description'])) ? \$attribute['description'] : 'none'; ?>
                                            </li>
                                            <li>
                                                Visibility: <?php echo (!empty(\$attribute['visibility'])) ? \$attribute['visibility'] : 'none'; ?>
                                            </li>
                                            <li>
                                                Static: <?php echo (\$attribute['isStatic'] === TRUE) ? 'yes' : 'no'; ?>
                                            </li>
                                            <li>
                                                Type: <?php echo (!empty(\$attribute['type'])) ? \$attribute['type'] : 'none'; ?>
                                            </li>
                                        </ul>
                                    </li>

                            <?php

                                    }
                                    echo ('</ul>');
                                } else {
                                    echo '<span>Attribute:</span> none.';
                                }
                                echo ('</div>');

                                echo ('<div>');
                                if (!empty(\$fileParsed['analysis']['method'])) {
                                    echo ('<span>Methods: </span> <ul>');
                                    foreach (\$fileParsed['analysis']['method'] as \$method) {

                            ?>

                                        <li>
                                            <?php echo \$method['name']; ?>
                                            <ul>
                                                <li>
                                                    </span>Description:</span> <?php echo (!empty(\$method['description'])) ? \$method['description'] : 'none'; ?>
                                                </li>
                                                <li>
                                                    <span>Visibility:</span> <?php echo (\$method['visibility']); ?>
                                                </li>
                                                <li>
                                                    <span>Static:</span> <?php echo (\$method['isStatic'] === TRUE) ? 'yes' : 'no'; ?>
                                                </li>
                                                <li>
                                                    <?php

                                                        if (!empty(\$method['param'])) {
                                                            echo ('<span>Arguments:</span><ul>');
                                                            foreach (\$method['param'] as \$param) {

                                                    ?>

                                                                <li>
                                                                    <?php echo (\$param['name']); ?>
                                                                    <ul>
                                                                        <li>
                                                                            <span>Description:</span> <?php echo (!empty(\$param['description'])) ? \$param['description'] : 'none'; ?>
                                                                        </li>
                                                                        <li>
                                                                            <span>Type:</span> <?php echo (!empty(\$param['type'])) ? \$param['type'] : 'none'; ?>
                                                                        </li>
                                                                    </ul>
                                                                </li>

                                                    <?php

                                                            }
                                                            echo ('</ul>');
                                                        } else {
                                                            echo ('<span>Argument:</span> none');
                                                        }

                                                    ?>
                                                </li>
                                                <li>
                                                    <?php

                                                        if (!empty(\$method['return'])) {
                                                            echo ('<span>Return:</span><ul>');
                                                            foreach (\$method['return'] as \$return) {

                                                    ?>

                                                                <li>
                                                                    <?php echo (\$return['name']); ?>
                                                                    <ul>
                                                                        <li>
                                                                            <span>Description:</span> <?php echo (!empty(\$return['description'])) ? \$return['description'] : 'none'; ?>
                                                                        </li>
                                                                        <li>
                                                                            <span>Type:</span> <?php echo (!empty(\$return['type'])) ? \$return['type'] : 'none'; ?>
                                                                        </li>
                                                                    </ul>
                                                                </li>

                                                    <?php

                                                            }
                                                            echo ('</ul>');
                                                        } else {
                                                            echo ('<span>Return:</span> void');
                                                        }

                                                    ?>
                                                </li>
                                            </ul>
                                        </li>


                            <?php

                                    }
                                    echo ('</ul>');
                                } else {
                                    echo '<span>Method:</span> none.';
                                }
                                echo ('</div>');

                            ?>
                        </div>
                    </div>

            <?php

                }

            ?>

            <aside>

            </aside>
        </section>

        <footer></footer>

    </body>
</html>
EOT;

        file_put_contents($docPath, $docHtmlContent);
    }
}