<?php

require_once('figures/king.php');
require_once('figures/queen.php');
require_once('figures/rook.php');
require_once('figures/bishop.php');
require_once('figures/knight.php');
require_once('figures/pawn.php');

abstract class Figure {
    protected GameState $game_state;
    protected int $position_index;
    protected int $col; 
    protected int $row; 
    protected int $figure; 
    protected int $color; 
    protected int $enemy_color; 

    public function __construct(GameState $game_state, int $position_index) {
        $this->game_state = $game_state;
        $this->position_index = $position_index;
        $this->col = Functions::positionToCol($position_index);
        $this->row = Functions::positionToRow($position_index);
        $this->figure = $game_state->position[$position_index];
        $this->color = Functions::color($this->figure);
        $this->enemy_color = $this->color == COLOR_BLACK ? COLOR_WHITE : COLOR_BLACK;
    }

    abstract public function getCandidateMoves();

    public function getAvailableMoves() {
        $moves = array();
        $board_position = new BoardPosition();
        $our_king_positions = $this->game_state->figures[FG_KING + $this->color];
        if (count($our_king_positions) === 0) {
            return $moves;
        }
        $our_king_position = $our_king_positions[0];
        $candidate_moves = $this->getCandidateMoves();
        foreach($candidate_moves as $to_index) {
            $to_position = $this->game_state->position; 
            $to_position[$this->position_index] = FG_NONE; 
            $to_position[$to_index] = $this->figure; 
            $board_position->setPosition($to_position);
            if ($board_position->isFieldUnderAttack($our_king_position, $this->enemy_color)) {

                continue;
            }
            $moves[] = $to_index;
        }
        return $moves;
    }

    public function makeMove($to_cell_index, $validate_move=true) {
        
        $this->game_state->setFigures();
        if ($validate_move) {
            $available_moves = $this->getAvailableMoves();
            if (!in_array($to_cell_index, $available_moves)) {
                $this->game_state->text_state = 'Недопустимый ход. Сделайте корректный ход';
                return false;
            }
        }

        $to_figure = $this->game_state->position[$to_cell_index];

        
        $this->game_state->position[$this->position_index] = FG_NONE;
        $this->game_state->position[$to_cell_index] = $this->figure;

        
        $this->game_state->crossed_field = null;
        
        
        if ($to_figure !== FG_NONE) {
            $this->game_state->non_action_semimove_counter = 0;
        } else {
            $this->game_state->non_action_semimove_counter++;
        }

        
        if ($this->color == COLOR_BLACK) {
            $this->game_state->move_number++;
            $this->game_state->current_player_color = COLOR_WHITE;
        } else {
            $this->game_state->current_player_color = COLOR_BLACK;
        }

        
        $this->game_state->prev_move_from = $this->position_index; 
        $this->game_state->prev_move_to = $to_cell_index;

        if($this->game_state->with_ai) { 
            if ($this->game_state->human_color == $this->color) { 
                $this->game_state->text_state = "#{$this->game_state->move_number} Ход компьютера, подождите";
            } else {
                $this->game_state->text_state = "#{$this->game_state->move_number} Ваш ход.";
            }
        }
        else {
            $color_text = ($this->color == COLOR_BLACK) ? 'Ход белых.' : 'Ход черных.'; 
            $this->game_state->text_state = "#{$this->game_state->move_number} {$color_text}";
        }
        
        return true;
    }

    public function getLongRangeCandidateMoves(array $shifts) {
        $moves = array();
        foreach ($shifts as $shift) {
            list($shift_col, $shift_row) = $shift;
            $continue_shift = true;
            $col = $this->col;
            $row = $this->row;
            while ($continue_shift) {
                $col = $col + $shift_col;
                if ($col < 0 || $col >= BOARD_SIZE) {
                    $continue_shift = false;
                    continue;
                }
                $row = $row + $shift_row;
                if ($row < 0 || $row >= BOARD_SIZE) {
                    $continue_shift = false;
                    continue;
                }
                $to_index = Functions::colRowToPositionIndex($col, $row);
                $to_figure = $this->game_state->position[$to_index];
                if ($to_figure === FG_NONE) {
                    $moves[] = $to_index;
                    continue;
                }
                $to_figure_color = Functions::color($to_figure);
                if ($to_figure_color == $this->enemy_color) {
                    $moves[] = $to_index;
                }
                $continue_shift = false;
            }
        }
        return $moves;
    }

    public function getShortRangeCandidateMoves(array $shifts) {
        $moves = array();
        foreach($shifts as $shift) {
            $col = $this->col + $shift[0];
            if ($col < 0 || $col >= BOARD_SIZE) {
                continue;
            }
            $row = $this->row + $shift[1];
            if ($row < 0 || $row >= BOARD_SIZE) {
                continue;
            }
            $to_index = Functions::colRowToPositionIndex($col, $row);
            $to_figure = $this->game_state->position[$to_index];
            if ($this->color == Functions::color($to_figure)) {
                continue;
            }
            $moves[] = $to_index;
        }
        return $moves;
    }
}
