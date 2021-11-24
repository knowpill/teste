<?php

use Exception;

class Jogos
{

    const DEZENAS_PERMITIDAS = [6, 7, 8, 9, 10];

    /**
     * @var int
     */
    private int $quantidadeDezenas;

    /**
     * @var array
     */
    private array $resultado;

    /**
     * @var int
     */
    private int $totalJogos;

    /**
     * @var array
     */
    private array $jogos;

    public function __construct(int $quantidadeDezenas, int $totalJogos)
    {
        $this->setQuantidadeDezenas($quantidadeDezenas);
        $this->setTotalJogos($totalJogos);

        $this->gerarJogos();
    }

    /**
     * Get totalJogos
     *
     * @return  int
     */
    public function getTotalJogos()
    {
        return $this->totalJogos;
    }

    /**
     * Set totalJogos
     *
     * @param  int  $totalJogos
     *
     * @return  self
     */
    public function setTotalJogos(int $totalJogos)
    {
        $this->totalJogos = $totalJogos;

        return $this;
    }

    /**
     * Get quantidadeDezenas
     *
     * @return  int
     */
    public function getQuantidadeDezenas()
    {
        return $this->quantidadeDezenas;
    }

    /**
     * Set quantidadeDezenas
     *
     * @param  int  $quantidadeDezenas
     *
     * @return  self
     */
    public function setQuantidadeDezenas(int $quantidadeDezenas)
    {
        if (!in_array($quantidadeDezenas, self::DEZENAS_PERMITIDAS)) {
            throw new Exception("Quantidade de dezenas inválida");
        }

        $this->quantidadeDezenas = $quantidadeDezenas;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return  array
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Set resultado
     *
     * @param  array  $resultado
     *
     * @return  self
     */
    public function setResultado(array $resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get jogos
     *
     * @return  array
     */
    public function getJogos()
    {
        return $this->jogos;
    }

    /**
     * Set jogos
     *
     * @param  array  $jogos
     *
     * @return  self
     */
    public function setJogos(array $jogos)
    {
        $this->jogos = $jogos;

        return $this;
    }

    /**
     * Gerar as dezenas
     *
     * @return  array
     */
    private function gerarDezenas($sorteio = false)
    {
        $tabelinha = range(1, 60);
        $sorteados = [];
        $i         = 0;

        $quantidadeDezenas = !$sorteio ? $this->getQuantidadeDezenas() : 6;

        while ($i++ < $quantidadeDezenas) {
            shuffle($tabelinha);
            $sorteados[] = array_pop($tabelinha);
        }

        sort($sorteados);

        return $sorteados;
    }

    /**
     * Gerar Jogo(s)
     *
     * @return  void
     */
    private function gerarJogos()
    {
        $jogos = [];
        $a     = 1;

        do {
            $jogos[] = $this->gerarDezenas();
        } while ($a++ < $this->getTotalJogos());

        $this->setJogos($jogos);
    }

    /**
     * Sorteio
     *
     * @return  void
     */
    public function sortear()
    {
        $this->setResultado($this->gerarDezenas(true));
    }

    /**
     * Conferir jogos
     *
     * @return  array
     */
    private function conferirJogos()
    {
        $acertos = [];
        foreach ($this->getJogos() as $jogo) {
            $acertos[] = array_intersect($this->getResultado(), $jogo);
        }

        return $acertos;
    }

    /**
     * Tabela de resultado
     * Normalmente eu utilizaria alguma view para fazer isso porém apenas para exemplificar
     *
     * @return mixed
     */
    public function tabelaResultado()
    {
        $acertos = $this->conferirJogos();

        $rows = "";

        foreach ($acertos as $jogo => $numeros) {
            ++$jogo;
            $rows .= "
                <tr>
                    <td>$jogo</td>
                    <td>".implode(', ', $numeros)."</td>
                </tr>
            ";
        }

        echo "
            <table>
                <thead>
                    <tr>
                        <th>Jogo</th>
                        <th>Acertos</th>
                    </tr>
                </thead>
                <tbody>
                    $rows
                </tbody>
            </table>
        ";
    }

}
