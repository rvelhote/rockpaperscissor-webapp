/**
 * MIT License
 *
 * Copyright (c) 2016 Ricardo Velhote
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
// 'use strict';

import React from 'react';
import Player from './PlayerComponent';
import Move from './MoveComponent';
import Stats from './StatsComponent';

import '../styles/Game.css';

class GameComponent extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            player: {
                move: '',
                handle: '@rvelhote'
            },
            game: {
                opponent: {
                    uuid: '',
                    handle: '',
                    picture: ''
                },
                moves: []
            },
            result: {
                move: '',
                winner: ''
            },
            stats: {
                win: 0,
                lose: 0,
                draw: 0
            },
            history: [],
            working: false
        };
    }

    componentDidMount() {
        this.requestNewGame();//.then(() => console.log(this.state));
    }

    onPlayClick(target) {
        var data = new FormData();
        data.append('form[move]', target.dataset.move);
        data.append('form[game]', this.state.game.guid);

        // this.setState({working: true});
        //
        var player = {
            handle: this.state.player.handle,
            move: target.dataset.move
        };

        var request = new Request('http://localhost:8080/play', {
            method: 'POST',
            body: data
        });

        return fetch(request)
            .then(response => response.json())
            .then(response => this.setState({ working: false, player: player, stats: response.stats, result: response.result, game: response.game }));
    }

    requestNewGame() {
        // this.setState({working: true});
        var request = new Request('http://localhost:8080/game', {
            method: 'POST'
        });
        return fetch(request).then(response => response.json()).then(response => this.setState({ working: false, stats: response.stats, game: response.game }));
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        var working;
        if(this.state.working) {
            working = 'working'
        }


        return (
            <section className="game-component col-lg-12">
                __ {working} __

                <div className="row">
                    <Stats win={this.state.stats.win} lose={this.state.stats.lose} draw={this.state.stats.draw}/>
                </div>
                <div className="row">
                    <div className="col-lg-5">
                        <Player player={this.state.player} />

                        {
                            this.state.game.moves.map((m) =>
                                <Move key={m.move} disabled={this.state.working} name={m.move} play={this.onPlayClick.bind(this)} />
                            )
                        }
                    </div>

                    <div className="col-lg-2">
                        <span className="versus">vs</span>
                    </div>

                    <div className="col-lg-5">
                        <Player player={this.state.game.opponent} />
                    </div>
                </div>
                <div className="row">
                    <div className="col-lg-12">
                        <ul>
                            <li>Opponent played: {this.state.result.move}</li>
                            <li>You played: {this.state.player.move}</li>
                            <li>Result: {this.state.result.outcome}</li>
                            <li>Result: {this.state.result.winner}</li>
                            <li>Game ID: {this.state.game.guid}</li>
                        </ul>
                    </div>
                </div>
            </section>
        );
    }
}

GameComponent.displayName = 'GameComponent';

// Uncomment properties you need
// GameComponent.propTypes = {};
// GameComponent.defaultProps = {};

export default GameComponent;
