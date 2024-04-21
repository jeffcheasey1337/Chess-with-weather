<?php

class King extends Figure {
    
    const FIELDS_CASTLING_WHITE_KING = array(61, 62);
    const FIELDS_CASTLING_WHITE_QUEEN = array(59, 58, 57);
    const FIELDS_CASTLING_BLACK_KING = array(5, 6);
    const FIELDS_CASTLING_BLACK_QUEEN = array(3, 2, 1);

    const FIELD_INIT_WHITE = 60; 
    const FIELD_INIT_BLACK = 4; 
    const TO_COLUMN_CASTLING_KING = 6; 

    protected BoardPosition $board_position;

    
    public function getCandidateMoves() {
        return $this->getShortRangeCandidateMoves(Queen::SHIFTS);
    }

    public function getAvailableMoves() {
        $moves = array();
        $this->board_position = new BoardPosition();

        
        $candidate_moves = $this->getCandidateMoves();
        foreach($candidate_moves as $to_index) {
            $to_position = $this->game_state->position; 
            $to_position[$this->position_index] = FG_NONE; 
            $to_position[$to_index] = $this->figure;
            $this->board_position->setPosition($to_position);
            if ($this->board_position->isFieldUnderAttack($to_index, $this->enemy_color)) {
               
                continue;
            }
            $moves[] = $to_index;
        }

       
        $this->board_position->setPosition($this->game_state->position);
        if ($this->color == COLOR_WHITE) {
            if ($this->game_state->enable_castling_white_king && $this->checkCastlingConditions(self::FIELD_INIT_WHITE, self::FIELDS_CASTLING_WHITE_KING)) {
                $moves[] = self::FIELDS_CASTLING_WHITE_KING[1];
            }
            if ($this->game_state->enable_castling_white_queen && $this->checkCastlingConditions(self::FIELD_INIT_WHITE, self::FIELDS_CASTLING_WHITE_QUEEN)) {
                $moves[] = self::FIELDS_CASTLING_WHITE_QUEEN[1];
            }
        } else {
            if ($this->game_state->enable_castling_black_king && $this->checkCastlingConditions(self::FIELD_INIT_BLACK, self::FIELDS_CASTLING_BLACK_KING)) {
                $moves[] = self::FIELDS_CASTLING_BLACK_KING[1];
            }
            if ($this->game_state->enable_castling_black_queen && $this->checkCastlingConditions(self::FIELD_INIT_BLACK, self::FIELDS_CASTLING_BLACK_QUEEN)) {
                $moves[] = self::FIELDS_CASTLING_BLACK_QUEEN[1];
            }
        }
        return $moves;
    }

    
    protected function checkCastlingConditions(int $init_position, array $fields_to_rook) {
        
        foreach ($fields_to_rook as $cell) {
            if ($this->game_state->position[$cell] !== FG_NONE) {
                return false;
            }
        }
       
        if (
            $this->board_position->isFieldUnderAttack($init_position, $this->enemy_color) ||
            $this->board_position->isFieldUnderAttack($fields_to_rook[0], $this->enemy_color) ||
            $this->board_position->isFieldUnderAttack($fields_to_rook[1], $this->enemy_color)
        ) {
            return false;
        }
        return true;
    }

    public function makeMove($to_cell_index, $validate_move=true) {
        $col = $this->col;
        if (!parent::makeMove($to_cell_index, $validate_move)) {
            return false;
        }

        
        if ($this->color == COLOR_WHITE) {
            $this->game_state->enable_castling_white_king = false;
            $this->game_state->enable_castling_white_queen = false;
        } else {
            $this->game_state->enable_castling_black_king = false;
            $this->game_state->enable_castling_black_queen = false;
        }
        
        $to_col = Functions::positionToCol($to_cell_index);
        if (abs($col - $to_col) == 2) {
            
            if ($to_col == self::TO_COLUMN_CASTLING_KING) {
                
                $rook_from_position = Functions::colRowToPositionIndex(BOARD_SIZE-1, $this->row);
                $rook_to_position = $to_cell_index - 1;
            } else {
                
                $rook_from_position = Functions::colRowToPositionIndex(0, $this->row);
                $rook_to_position = $to_cell_index + 1;
            }
            $this->game_state->position[$rook_from_position] = FG_NONE;
            $this->game_state->position[$rook_to_position] = FG_ROOK + $this->color;
        }
        return true;
    }
}
