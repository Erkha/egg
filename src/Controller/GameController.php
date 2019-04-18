<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ItemManager;

/**
 * Class ItemController
 *
 */
class GameController extends GuzzleController
{
    const     RARITY_VALUE =['junk'=>0,
                        'basic'=>1,
                        'fine' =>2,
                        'masterwork' =>3,
                        'rare'=>5,
                        'exotic'=>8,
                        'ascended'=>13,
                        'legendary'=>21];
    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['play']) && $_POST['play']== true) {
                // récupérer un oeuf
                $egg=$this->eggRandom();
                $egg->rarityValue = self::RARITY_VALUE[$egg->rarity];
                //si junk
                if ($egg->rarity == "junk") {
                    // affiche la vue avec oeuf perdant et passage au joueur suivant
                        // ( init compteur secondaire de l'autre joueur)
                    $_SESSION['intermediateCounter']=0;
                    $_SESSION['player'] == "player_1"?$_SESSION['player'] = 'player_2'
                                                    :$_SESSION['player'] = 'player_1';
                } else {//sinon
                    // ajoute la valeur au compteur secondaire
                    $_SESSION['intermediateCounter']+= self::RARITY_VALUE[$egg->rarity];
                }
                $perso1=$this->characterId($_SESSION['perso1']);
                $perso2=$this->characterId($_SESSION['perso2']);
                return $this->twig->render('Home/game.html.twig', ['session' => $_SESSION,
                                                                    'egg'=>$egg,
                                                            'perso1' =>$perso1,
                                                            'perso2' =>$perso2]);
            }
            if (isset($_POST['hold']) && $_POST['hold']== true) {
                // ajoute le compteur secondaire au compteur principal
                $perso1=$this->characterId($_SESSION['perso1']);
                $perso2=$this->characterId($_SESSION['perso2']);
                $_SESSION['player']== "player_1" ? $_SESSION['player1']+= $_SESSION['intermediateCounter']
                                                : $_SESSION['player2']+= $_SESSION['intermediateCounter'];
                //Vérifie si le compteur principal atteint 100
                if ($_SESSION['player1']>99 || $_SESSION['player2']>99) {
                    $_SESSION['player1']>99 ? $_SESSION['winner'] = "player 1" : $_SESSION['winner'] = "player 2";
                    return $this->twig->render('Home/win.html.twig', ['session' => $_SESSION]);
                }

                // init le compteur secondaire de l'autre joueur, affiche le joueur suivant
                $perso1=$this->characterId($_SESSION['perso1']);
                $perso2=$this->characterId($_SESSION['perso2']);
                $_SESSION['intermediateCounter'] = 0;
                $_SESSION['player'] == "player_1"?$_SESSION['player'] = 'player_2'
                                                :$_SESSION['player'] = 'player_1';
                return $this->twig->render('Home/game.html.twig', ['session' => $_SESSION,
                                                            'perso1' =>$perso1,
                                                            'perso2' =>$perso2]);
            }
        }
        // ici c'est le 1er round
        $_SESSION['player'] = "player_1";
        $_SESSION['intermediateCounter'] = 0;
        $_SESSION['player1']=0;
        $_SESSION['player2']=0;
        $_SESSION['username1']="Kevin";
        $_SESSION['username2']="JDBOC";
        $_SESSION['perso1']="5cac51240d488f0da6151c31";
        $_SESSION['perso2']="5cac51240d488f0da6151c32";
        $perso1=$this->characterId($_SESSION['perso1']);
        $perso2=$this->characterId($_SESSION['perso2']);
        var_dump($_SESSION['perso1']);
        return $this->twig->render('Home/game.html.twig', ['session' => $_SESSION,
                                                            'perso1' =>$perso1,
                                                            'perso2' =>$perso2]);
        ;
    }
}
