<!-- card-match-tournament.twig partial -->
<div class="card card-match-tournament">


    {% if loop.index <= 1 and cardmatch is not defined or loop.index is not defined and cardmatch is not defined %}
    <link href="/resources/assets/css/components/partials/card-match.css?v={{version}}" rel="stylesheet"/>
    {% endif %}       

    <div class="card__inner-tournament flex flex--column">

        <div class="section-title-ottavo">        
            {% if phase.macro_matches|length == 8 %}
            {{index+1}}o Ottavo
            {% elseif phase.macro_matches|length == 4 %}
            {{index+1}}o Quarto
            {% elseif phase.macro_matches|length == 2 %}
            {{index+1}}a Semifinale
            {% else %}
            Finale
            {% endif %}
        </div>

        <div style="justify-content: center; -webkit-justify-content: center;" class="flex">
            <div class="card-match__team-tournament">                                                                
                <div class="player">
                    <div class="divTable" style="display: table-cell;justify-content: space-between;">
                        <div class="divTableBody">
                            <div class="divTableRow" style=" justify-content: space-between;">
                                {% if macroMatch.team1.name is not empty %}                                    
                    
                                    {% set avatar = '' %}
                                    {% for meta in macroMatch.team1.club.user.metas %}
                                        {% if meta.meta == 'avatar' %}
                                            {% set avatar = '/storage/' ~ meta.meta_value  %}
                                        {% endif %}
                                    {% endfor %}


                                    <div class="divTableCell" style="display: table-cell;vertical-align: middle;padding: 3px;/* max-width: 150px; *//* min-width: 150px; */text-align: right;">
                                        <div class="player__name" style="font-size: 13px;min-width: 70px;max-width: 70px;text-align: right;">
                                            <a href="">{{ macroMatch.team1.name }}</a>
                                        </div>
                                    </div>

                                    <div class="divTableCell" style="display: table-cell;vertical-align: middle;padding: 3px;min-width: 35px;">
                                        <div style="border: 2px solid #fff;width: 35px;border-radius: 50%;overflow: hidden;">
                                            <picture data-link="">
                                                <img class="lazyload" data-srcset="{{ avatar }}, {{ avatar }} 2x" alt="{{ macroMatch.team1.name }}"/>
                                            </picture>
                                            <noscript>
                                                <picture>
                                                    <!--[if IE 9]><video style="display: none;"><![endif]-->
                                                    <source
                                                    srcset="{{ avatar }}" media="(max-width: 736px)"/>
                                                    <!--[if IE 9]></video><![endif]-->
                                                    <img src="{{ avatar }}" alt="{{ player.name }} {{ player.surname }}"/>
                                                </picture>
                                            </noscript>
                                        </div>
                                    </div>
                                {% endif %}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="vs-tournament">
                <span>VS</span>
            </div>
            <div class="card-match__team-tournament">                                                                
                <div class="player">
                    <div class="divTable" style="display: table-cell;justify-content: space-between;">
                        <div class="divTableBody">
                            <div class="divTableRow" style=" justify-content: space-between;">                

                                {% if macroMatch.team2.name is not empty %}
                                
                                    {% set avatar = '' %}
                                    {% for meta in macroMatch.team2.club.user.metas %}
                                        {% if meta.meta == 'avatar' %}
                                            {% set avatar = '/storage/' ~ meta.meta_value  %}
                                        {% endif %}
                                    {% endfor %}

                                    <div class="divTableCell" style="display: table-cell;vertical-align: middle;padding: 3px;min-width: 35px;">
                                        <div style="border: 2px solid #fff;width: 35px;border-radius: 50%;overflow: hidden;">
                                            <picture data-link="/players/{{ player.id }}/show">
                                                <img class="lazyload" data-srcset="{{ avatar }}, {{ avatar }} 2x" alt="{{ player.name }} {{ player.surname }}"/>
                                            </picture>
                                            <noscript>
                                                <picture>
                                                    <!--[if IE 9]><video style="display: none;"><![endif]-->
                                                    <source
                                                    srcset="{{ avatar }}" media="(max-width: 736px)"/>
                                                    <!--[if IE 9]></video><![endif]-->
                                                    <img src="{{ avatar }}" alt="{{ player.name }} {{ player.surname }}"/>
                                                </picture>
                                            </noscript>
                                        </div>
                                    </div>

                                    <div class="divTableCell" style="display: table-cell;vertical-align: middle;padding: 3px;/* max-width: 150px; *//* min-width: 150px; */text-align: right;">
                                        <div class="player__name" style="font-size: 13px;min-width: 70px;max-width: 70px;text-align: left;">
                                            <a href="">{{ macroMatch.team2.name }}</a>
                                        </div>
                                    </div>
                                
                                {% endif %}

                            </div>
                        </div>
                    </div>
                </div>                                                                                               
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
