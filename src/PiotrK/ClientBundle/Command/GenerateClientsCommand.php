<?php
namespace PiotrK\ClientBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PiotrK\ClientBundle\Entity\Client;
use PiotrK\ClientBundle\Entity\ClientOrder;
use PiotrK\ClientBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;

class GenerateClientsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('piotrk:generate-client')
            ->setDescription('Generate clients')
            ->addOption('clients', null, InputOption::VALUE_REQUIRED, 'How many client would you like to generate?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clients = $input->getOption('clients');
        if ($clients and is_numeric($clients)) {
          if($clients > 1000){
            $text = 'Clients count must be lower then 1000';
            $output->writeln($text);
            return;
          }else{
            $text = 'Generating ' . $clients . ' clients';
          }
        } else {
            $text = 'piotrk:generate-client --clents {value} options is required, value must be > 0';
            $output->writeln($text);
            return;
        }


        $dm = $this->getContainer()->get('doctrine')->getManager();
        $dm->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->truncateTables($dm);

        $clientsCollection = new ArrayCollection();
        

        for($i = 0; $i < $clients; $i++){
            $newClient = new Client();
            $newClient
                ->setName('Client ' . $i)
                ->setLastName('LastName ' . $i);
            $clientsCollection->add($newClient);
            $dm->persist($newClient);
        }
        $dm->flush();
        $ordersCollection = new ArrayCollection();
        $output->writeln('Creating orders for clients');
        foreach($clientsCollection as $client){
            $orderCount = rand(1 , 5);
            for($orderNo = 0; $orderNo < $orderCount; $orderNo++){
                $newOrder = new ClientOrder();
                $date = new \DateTime();
                $date->setTimestamp(rand(1400000000, $date->getTimestamp()));  
                $newOrder->setCreated($date);
                $newOrder->setClient($client);
                $ordersCollection->add($newOrder);
                $client->addOrder($newOrder);
                $dm->persist($newOrder);
            }
            
            $dm->flush();
        }

        $output->writeln('Adding products to orders');
        foreach($ordersCollection as $order){
            $productCount = rand(1, 10);
            for($productNo = 0; $productNo < $productCount; $productNo++){
                    $i++;
                    $newProduct = new Product();
                    $newProduct->setCost((float) rand(1, 100));
                    $newProduct->setName('Product: ' . $productNo);
                    $newProduct->setOrder($order);
                    $order->addProduct($newProduct);
                    $dm->persist($newProduct);
                }

            $dm->flush();
        }
        $output->writeln(memory_get_peak_usage());
        $output->writeln('Generate finished succesfully');
    }

    protected function truncateTables($dm){
        $connection = $dm->getConnection(); 
        $schemaManager = $connection->getSchemaManager(); 
        $tables = $schemaManager->listTables(); 

        $query = sprintf('SET FOREIGN_KEY_CHECKS = 0;');
        $connection->executeQuery($query, array(), array());

        $query = ''; 

        foreach($tables as $table) { 
            $name = $table->getName(); 
            $query .= 'TRUNCATE ' . $name . ';'; 
        } 

        $connection->executeQuery($query, array(), array());

        $query = sprintf('SET FOREIGN_KEY_CHECKS = 1;');
        $connection->executeQuery($query, array(), array());
    }
}