<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 07/11/2015
 * Time: 06:21
 */

namespace Dao\DataSourceBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class CompareKeyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dao:compare:key')
            ->setDescription('compare all key in parameters.yml file with all environment')
            ->addArgument(
                'key',
                InputArgument::OPTIONAL,
                'What keys will be compared?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');

        $origin = 'parameters.yml';
        $compareWith = array('parameters.yml', 'parameters_dev.yml', 'parameters_test.yml', 'parameters_prod.yml');
        $configPath = $this->getContainer()->get('kernel')->getRootDir() . '/config';

        $yaml = new Parser();
        $originValue = $yaml->parse(file_get_contents($configPath . '/' . $origin));

        //compare all keys
        $inValidEnvironment = array();
        $isMiss = false;
        $missKeys = array();
        if(empty ($key)) {
            foreach($compareWith as $e) {
                $envValue = $yaml->parse(file_get_contents($configPath . '/' . $e));
                if(! $this->compareKey($originValue['parameters'], $envValue['parameters'], $missKeys)) {
                    $inValidEnvironment[] = $e;
                    $isMiss = true;
                }
            }
        } else {
            foreach($compareWith as $e) {
                $envValue = $yaml->parse(file_get_contents($configPath . '/' . $e));
                if(! $this->compareKey($originValue['parameters'], $envValue['parameters'], $missKeys, $key)) {
                    $inValidEnvironment[] = $e;
                    $isMiss = true;
                }
            }
        }

        # Exit status 0 returned because command executed successfully.
        $statusCode = 0;
        if(empty ($key)) {
            $text = "All keys are the same"
                . "\nAll environment are compared: ". implode(', ', $compareWith)
                . "\n=>OK";
        } else {
            $text = "Key [{$key}] is the same"
                . "\nAll environment are compared: ". implode(', ', $compareWith)
                . "\n=>OK";
        }

        if($isMiss) {
            $text = "Missed keys are:\n" . implode("\n", $missKeys)
                . "\nEnvironments are missed: ". implode(', ', $inValidEnvironment)
                . "\n=>FAILED";
            # Non-zero exit status returned -- command failed to execute.
            $statusCode = 400;
        }

        $output->writeln($text);
        return $statusCode;

    }

    /**
     * @param $origin
     * @param $with
     * @param $missKeys
     * @param null $key
     * @return bool
     */
    private function compareKey($origin, $with, &$missKeys, $key = null)
    {
        $missKeys = array();
        //compare all keys
        if(empty($key)) {
            $missKeys = array_keys(array_diff_key($origin, $with));
            return empty($missKeys);
        }

        $existedInOrigin = in_array($key, array_keys($origin));
        $existedInWith = in_array($key, array_keys($with));
        if(! ($existedInOrigin && $existedInWith)) {
            $missKeys[$key] = $key;
            return false;
        }
        return true;
    }
}