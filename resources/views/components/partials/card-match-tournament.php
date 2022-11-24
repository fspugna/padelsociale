<!-- card-match-tournament.twig partial -->
<div class="card card-match-tournament">


    {% if loop.index <= 1 and cardmatch is not defined or loop.index is not defined and cardmatch is not defined %}
    <link href="/resources/assets/css/components/partials/card-match.css?v={{version}}" rel="stylesheet"/>
    {% endif %}


    {% set my_match = false %}
    {% for teamPlayer in match.team1.players %}
    {% if current_user.id == teamPlayer.id_player %}
    {% set my_match = true %}
    {% endif %}
    {% endfor %}

    {% if my_match == false %}
    {% for teamPlayer in match.team2.players %}
    {% if current_user.id == teamPlayer.id_player %}
    {% set my_match = true %}
    {% endif %}
    {% endfor %}
    {% endif %}

    <div class="card__inner-tournament flex flex--column">

        <div class="section-title-ottavo">        
            {% if phase.matches|length == 8 %}
            {{index+1}}o Ottavo
            {% elseif phase.matches|length == 4 %}
            {{index+1}}o Quarto
            {% elseif phase.matches|length == 2 %}
            {{index+1}}a Semifinale
            {% else %}
            Finale
            {% endif %}
        </div>

        <div style="justify-content: center; -webkit-justify-content: center;" class="flex">
            <div class="card-match__team-tournament">                                
                {% if scores[phase.id][match.id] == null %}
                {% for teamPlayer in match.team1.players %}
                {% if teamPlayer.starter %}

                {% set avatar = '' %}
                {% for meta in teamPlayer.player.metas %}
                {% if meta.meta == 'avatar' %}
                {% set avatar = '/storage/' ~ meta.meta_value  %}
                {% endif %}
                {% endfor %}
                {% if avatar == '' %}
                {% if teamPlayer.player.gender == 'f' %}
                {% set avatar = 'https://cdn.xl.thumbs.canstockphoto.es/padel-mujer-jugador-vectores-eps_csp69229035.jpg' %}
                {% else %}
                {% set avatar = 'https://comps.canstockphoto.es/padel-jugador-figura-vectores-eps_csp61076734.jpg' %}
                {% endif %}
                {% endif %}

                {% set player = teamPlayer.player %}
                {% include 'components/partials/card-match-player-left.twig' %}

                {% endif %}
                {% endfor %}

                {% else %}

                {# Elenco giocatori che hanno effettivamente giocato la partita #}
                {% for player in match_players[match.id]['team1'] %}
                {% include 'components/partials/card-match-player-left.twig' %}
                {% endfor %}

                {% endif %}


            </div>
            <div class="vs-tournament">
                <span>VS</span>
            </div>
            <div class="card-match__team-tournament">
                {% if scores[phase.id][match.id] == null %}
                {% for teamPlayer in match.team2.players %}
                {% if teamPlayer.starter %}

                {% set player = teamPlayer.player %}
                {% include 'components/partials/card-match-player-right.twig' %}

                {% endif %}
                {% endfor %}

                {% else %}

                {# Elenco giocatori che hanno effettivamente giocato la partita #}
                {% for player in match_players[match.id]['team2'] %}
                {% include 'components/partials/card-match-player-right.twig' %}
                {% endfor %}

                {% endif %}

            </div>
        </div>

        <div class="card__risultato-tournament">            
            {% if scores[phase.id][match.id] == null and match.id_team1 is not empty and match.id_team2 is not empty %}
            <span style="margin-top: 0px; margin-bottom: 35px;" class="section-title section-title--no-separator section-title--small">Da Disputare</span>

                {% if match.id_club and match.scores is empty %}
                    {% if my_match %}
                        {# include 'components/partials/risultato-incontro.twig' #}
                    {% endif %}
                {% endif %}
                
            {% else %}

                {% if match.id_team1 is not empty and match.id_team2 is not empty %}
                    {% if match.a_tavolino == 0 %}
                        <div class="card__title__risultato-tournament">Risultato Finale</div>
                    {% elseif match.a_tavolino == 1 %}
                        <div class="card__title__risultato-tournament">Risultato a tavolino</div>
                    {% endif %}

                    <div class="match__data-tournament">
                        {% if match.scores %}
                            {% if scores %}
                                {% for curset in 1..5 %}
                                    {% if scores[phase.id][match.id][curset] %}
                                    <div>
                                        <label style="font-size: 18px; color: #FFF; margin: auto 10px;">
                                            {{ scores[phase.id][match.id][curset]['team1'] }} - {{ scores[phase.id][match.id][curset]['team2'] }}
                                        </label>
                                    </div>
                                    {% endif %}
                                {% endfor %}
                            {% endif %}
                        {% endif %}
                    </div>
                {% endif %}
                
                <p class="abstract_match-tournament">
                    {{ match.note }}
                </p>

                {% if match.id_club and match.scores is not empty %}
                    {% if my_match %}
                        {# include 'components/partials/risultato-incontro.twig' #}
                    {% endif %}
                {% endif %}

            {% endif %}

            {% if match.a_tavolino == 0 %}
            <div class="card__separator-tournament">
                <div class="player-tournament">
                    <div class="divTable" style="    margin-top: -5px; margin-bottom: -25px;">
                        <div class="divTableBody">
                            <div class="divTableRow">
                                <div class="divTableCell" style="display: table-cell;vertical-align: middle;  max-width: 80%;">

                                    <div style="overflow: hidden;">


                                        <div class="divTableCell" style="display: table-cell; vertical-align: middle; padding: 3px;">
                                            <table style="">
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align: left; font-size: 18px;color: #FFE200;"></td>
                                                    </tr>
                                                    <tr>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>


                                <div class="divTableCell" style="display:table-cell;vertical-align:middle;   max-width:160px; text-align: center;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {% endif %}


            <div class="card-match__footer-tournament card-match__footer--inputDate">

                <div class="cta-container cta-container--center"></div>
                {% if my_match and match.scores is empty %}
                    {# include 'components/partials/programma-incontro.twig' #}
                {% endif %}
            </div>


        </div>

    </div>

    <style>
        /*STYLE CARD*/
        .section-title {
            display: block;
            color: #FFE200;
            font-size: 18px;
            padding: 0px 0px 0px 10px;
            text-align: center;
            border-bottom: 0px solid #fff;
            /*margin: -5px 0px 10px 0px;*/
        }
        .section-title-ottavo {
            color: #FFE200;
            font-size: 18px;
            padding: 0px 0px 0px 10px;
            text-align: left;
            border-bottom: 1px solid #fff;
            /*margin: -5px 0px 10px 0px;*/
        }
        .card-match__team-tournament {
            width: 100%;
            display: grid;
            display: -webkit-grid;
            justify-content: space-around;
            -webkit-justify-content: space-around;
        }

        .card.card-match-tournament {
            display: flex;
            justify-content: center;
            display: -webkit-flex;
            -webkit-justify-content: center;
        }

        article.card, div.card {
            background-color: rgba(0, 0, 0, 0.6);
            position: relative;
            border-radius: 8px;
            border: 2px solid #fff;
            margin: 15px auto;
            min-height: 50px;
        }
        .card-match-tournament {
            background: none !important;
            border: none !important;
            /*margin: 0 0px 10px !important;*/
            width: 100%;
        }
        .card-match-tournament .card__inner-tournament {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            border: 4px solid #fff;
            display: flex;
            display: -webkit-flex;
            padding: 0px 10px;
            justify-content: space-evenly;
            -webkit-justify-content: space-evenly;
            min-width: 306px;
            min-height: 208px;
        }
        .card-match__team-tournament {
            width: 100%;
            display: grid;
            display: -webkit-grid;
            justify-content: space-around;
            -webkit-justify-content: space-around;
        }
        .card-match-tournament-tournament .player-tournament {
            margin-bottom: 0px;
        }
        div.player-tournament {
            justify-content: left !important;
        }
        .card-match-tournament .player-tournament .player__name-tournament {
            color: #fff;
            font-family: "Courgette", serif;
            font-weight: bold;
        }
        .player__name-tournament {
            font-size: 12px;min-width: 70px;max-width: 70px;
        }

        .card-match-tournament .vs-tournament {
            display: flex;
            display: -webkit-flex;
            flex-direction: column;
            align-items: center;
            color: #FFE200;
            font-size: 30px;
            font-weight: bold;
            padding: 6px;
        }
        .card-match-tournament .vs-tournament:before, .card-match-tournament .vs-tournament:after {
            content: '';
            flex: 1;
            width: 1px;
            background: #ffffff;
            max-height: 15px;
        }
        .card__risultato-tournament {
            text-align: center;
            color: #FFFFFF;
            margin-top: 10px;
            margin-bottom: -10px;
        }
        .card__title__risultato-tournament {
            color: #FFE200; text-align: center; font-size: 20px; margin: -15px auto;
        }
        .match__data-tournament {
            border-radius: 4px;
            display: inline-flex;
            justify-content: space-between;
            -webkit-justify-content: space-between;
            color: #ffffff;
            /* padding: 4px; */
            margin-bottom: -5px;
        }
        p.abstract_match-tournament {
            font-size: 18px;
            margin-top: 5px;
            /*margin-bottom: 10px;*/
            color: #FFF;
            text-align: center;
        }
        .card__separator-tournament {
            border-top: 0px solid white;
            margin-top: -5px;
        }
        .card-match__footer-tournament.card-match__footer--inputDate {
        }
        .cta-container--center {
            text-align: center;
            width: 100%;
        }
        .cta-container {
            margin: 12px 0 0px;
        }
        @media screen and (max-width: 320px) {
            .card__inner-tournament.flex.flex--column {
                padding: 0px 4px;
            }
        }
        /*FINE STYLE CARD*/
    </style>
