<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\IOExceptionInterface;

#[AsCommand(
    name: 'app:import-csv',
    description: 'Import the csv located at public/products.csv in the product table.',
)]
class ImportCsvCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();

        if(!$fs->exists('public/products.csv')){
            $io->error('Fichier non trouvé.');
            return Command::FAILURE;
        }

        $result = [];
    
        if (($handle = fopen('public/products.csv', "r")) !== false) {
            $headers = fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== false) {
                $result[] = array_combine($headers, $data);
            }
            
            fclose($handle);
        }
        
        foreach ($result as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice((float) $data['price']);
            $product->setImageUrl($data['image_url']);

            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();

        $io->success('Produits importés avec succès.');

        return Command::SUCCESS;
    }
}
