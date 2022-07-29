<?php

namespace App\Controller;

use App\Domain\ReadModel\Result\VoteBasedResultRepository;
use App\Domain\WriteModel\Pokemon\Pokemon;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ResultController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly VoteBasedResultRepository $voteBasedResultRepository,
    )
    {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $template = $this->twig->load('results.html.twig');
        $response->getBody()->write($template->render([
            'results' => $this->voteBasedResultRepository->getResults(Pokemon::MAX_ID)
        ]));

        return $response;
    }
}