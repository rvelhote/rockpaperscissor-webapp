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

const MoveCollectionComponent = props =>
  <ul className="move-collection">
    { props.moves.map(m => <Move key={m.slug} movePlayer1={props.movePlayer1} movePlayer2={props.movePlayer2} onPlayClick={props.onPlayClick} gameset={props.gameset} game={props.game} name={m.slug} />) }
  </ul>;

MoveCollectionComponent.displayName = 'MoveCollectionComponent';

MoveCollectionComponent.propTypes = {
  moves: React.PropTypes.arrayOf(React.PropTypes.object),
  game: React.PropTypes.string
};

MoveCollectionComponent.defaultProps = {};

export default MoveCollectionComponent;
