<?php
require_once './app/core/App.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

core\App::autoloader();

$console = new Application();

$console
    ->register('orm')
    ->setDefinition(array(
      new InputArgument('ormCommand', InputArgument::OPTIONAL, 'What Command?'),
      new InputArgument('option', InputArgument::OPTIONAL, 'What Command?')
    ))
    ->setDescription('')
    ->setHelp('')
    ->setCode(function (InputInterface $input, OutputInterface $output) {

      if($input->getArgument('ormCommand') == 'generate'){
        $responseDefault = 'Error database config';
        exec('"vendors/bin/phpmig" '.$input->getArgument('ormCommand').' '.$input->getArgument('option'),$response);

        if(empty($response))
            $response = $responseDefault;

      }
      elseif($input->getArgument('ormCommand') == 'migrate'){
        exec('"vendors/bin/phpmig" '.$input->getArgument('ormCommand'),$response);

        $builder = new \core\components\Builder();
        $database = \core\components\Database::getInstance();
        $tableList = $database->getSchemaManager()->listTableNames();
        $indexToDel = array_search('migrations',$tableList);
        unset($tableList[$indexToDel]);
        $entityList = array_values($tableList);

        foreach($entityList as $key => $entity)
        {
          $entityList[$key] = $class = substr(ucfirst($entity),0,-1);
          $builder->controller($entityList[$key]);
          $builder->model($entityList[$key]);
        }

        $builder->modelList($entityList);
      }
      elseif($input->getArgument('ormCommand') == 'build'){

      }

      $output->writeln($response);
    });

$console
    ->register('generate')
    ->setDefinition(array(
        new InputArgument('ctrlCommand', InputArgument::OPTIONAL, 'What Command?'),
        new InputArgument('option', InputArgument::OPTIONAL, 'What Command?')
    ))
    ->setDescription('')
    ->setHelp('')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $builder = new \core\components\Builder();
        if($input->getArgument('ctrlCommand') == 'controller'){
            $res = $builder->customController($input->getArgument('option'));

            $output->writeln($res);
        }
    });

// Docgen
$console
    ->register('docgen')
    ->setDescription('generate a documentation')
    ->setHelp('')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $docGen = new \utils\docgen\DocGen();
        $docGen->create();

        $output->writeln('Documentation generated. Route "/app/documentation"');
    });

$console->run();