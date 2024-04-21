<?php

class GameState {
    const PROPERTY_NAMES = array(
        'with_ai', 'position', 'current_player_color', 'enable_castling_white_king', 'enable_castling_black_king', 'enable_castling_white_queen', 'enable_castling_black_queen',
        'crossed_field', 'non_action_semimove_counter', 'move_number', 'human_color', 'prev_move_from', 'prev_move_to', 'text_state', 'whiteID', 'blackID'
    );

    public $with_ai = true; 
    public $position = null; 
    public $current_player_color = null; 
    public $enable_castling_white_king = null; 
    public $enable_castling_white_queen = null; 
    public $enable_castling_black_king = null; 
    public $enable_castling_black_queen = null; 
    public $crossed_field = null; 
    public $non_action_semimove_counter = null; 
    public $move_number = null;

    public $human_color = null;
    public $prev_move_from = null; 
    public $prev_move_to = null; 
    public $text_state = null; 
    public $whiteID = null; 
    public $blackID = null; 

    public $figures = null; 

    private function getHash() {
        $result = array();
        foreach (self::PROPERTY_NAMES as $key) {
            $result[$key] = $this->$key;
        }
        return $result;
    }

    public function serializeState() {
        return json_encode($this->getHash(), JSON_UNESCAPED_UNICODE);
    }

    public function unserializeState(string $serialized_state) {
        try {
            $data = json_decode($serialized_state, true);
            foreach (self::PROPERTY_NAMES as $key) {
                $this->$key = $data[$key];
            }
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function setFigures() {
        $this->figures = array(
            FG_KING + COLOR_WHITE => array(),
            FG_KING + COLOR_BLACK => array(),
            FG_QUEEN + COLOR_WHITE => array(),
            FG_QUEEN + COLOR_BLACK => array(),
            FG_ROOK + COLOR_WHITE => array(),
            FG_ROOK + COLOR_BLACK => array(),
            FG_BISHOP + COLOR_WHITE => array(),
            FG_BISHOP + COLOR_BLACK => array(),
            FG_KNIGHT + COLOR_WHITE => array(),
            FG_KNIGHT + COLOR_BLACK => array(),
            FG_PAWN + COLOR_WHITE => array(),
            FG_PAWN + COLOR_BLACK => array()
        );
        for($i = 0; $i < BOARD_SIZE*BOARD_SIZE; $i++) {
            $figure_code = $this->position[$i];
            if ($figure_code !== FG_NONE) {
                $this->figures[$figure_code][] = $i;
            }
        }
    }
}
