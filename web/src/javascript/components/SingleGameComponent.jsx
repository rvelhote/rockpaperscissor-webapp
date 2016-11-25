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
import React from 'react';

import Move from './MoveComponent';

const SingleGameComponent = props => (
  <article className="game">
    <header className="game__header">
      <h1 className="game__header game__game-type-name">{props.game.game_type.name}</h1>
      <p className="game__header game__opponent">{props.game.player2.username}</p>
    </header>
    <main>
      <div>Player2 Name: </div>
      <div>Date: {props.game.date_played}</div>
      <div>Result: {props.game.result}</div>

      {
        props.game.game_type.move_types.map((m) => {
          return <Move key={m.slug}

                       gameset={props.gameset.guid}
                       game={props.game.guid}
                       name={m.slug} />
        })
      }
    </main>
  </article>
);


SingleGameComponent.displayName = 'SingleGameComponent';

SingleGameComponent.propTypes = {

};

SingleGameComponent.defaultProps = {};

export default SingleGameComponent;
