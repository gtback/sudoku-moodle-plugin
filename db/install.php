<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file replaces the legacy STATEMENTS section in db/install.xml,
 * lib.php/modulename_install() post installation hook and partially defaults.php
 *
 * @package    mod
 * @subpackage sudoku
 * @copyright  2011 Your Name <your@email.adress>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Post installation procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_sudoku_install() {
    global $DB;

    // Insert 
    $records = array(
        array_combine(array('name', 'description'), array('Hint', 'Cheater!')),
        array_combine(array('name', 'description'), array('Guess', 'You should never have to guess to make a decision')),
        array_combine(array('name', 'description'), array('Naked Single Candidates', '<p>Every Sudoku puzzle will have cells that have only one possible candidate. If there aren\'t any other candidates showing, Sudoku players call this a naked single. </p><p>Every naked single allows us to safely eliminate that number from all other cells in the row, column, and region that the naked single lies in. The logic is simple. If there is one cell that contains a single candidate, then that candidate is the solution for that cell. Below is an example of a naked single.</p><img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/row_naked_single.gif" align="left" width="273" height="41" alt="Naked Single Sudoku Tip"><p>In the example to the left, you can see the naked single is the nine. All the other nines may be crossed off leaving a 6,8 pair, 6,7 pair, a single 7, and a 4,6,8 triple. If you didn\'t pencil in all the possible candidates, the naked nine would be less obvious.</p><p>No doubt you also noted in this example that once you solved for the naked nine, the 7,9 pair\'s solution became a naked single. The 7,9 pair is called a hidden single. Below is another example of a hidden single.</p>')),
        array_combine(array('name', 'description'), array('Hidden Singles', '<img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/column_hidden_single.gif" align="left" width="42" height="273" alt="Hidden Single Sudoku Tips"><p>In the example at the left there are two hidden singles. Hidden singles have only one place they can go. The extra candidates in the cell "hide" the single solution. </p><p>In this example, the third cell from the top is a seven. Likewise in the bottom cell the only number that can go there is a four. </p><p>When there is a lot of candidates showing from the surrounding rows, columns, and regions, a hidden single can be hard to spot. Hidden singles will occur often.</p>')),
        array_combine(array('name', 'description'), array('Naked Pairs','<img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/row_naked_pair.gif" align="left" width="273" height="43" alt="naked pair Sudoku tips"><p>In the example to the left there is a "naked pair". A naked pair is two identical candidates in a particular row, column, or region. This combination of candidates will occur often also. </p><p>When you see a naked pair, it is safe to eliminate those two numbers from all other cells in the row, column, or region the pair reside in.</p><p>In the naked pair example it is safe to eliminate the four and six from the two quads of 3,4,6, and 8. Doing so, leaves two 3,8 pairs. The 3,4,6, and 8 quads are really "hidden pairs". </p>')),
        array_combine(array('name', 'description'), array('Hidden Pairs', '<img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/region_hidden_pair.gif" align="left" width="105" height="105" alt="Hidden Pair Sudoku tips"><p>In the example at the left there is a hidden pair 2 and 9. They are circled in red. Hidden pairs are identified by the fact that a pair of numbers occur in only two cells of a row, column, or region. They are "hidden" because the other numbers in the two cells make their presence harder to spot.</p><p>It is safe to remove all other digits from the two cells circled in red so that only the two and nine remain. Hidden pairs will appear often in your Sudoku puzzles and games.</p>')),
        array_combine(array('name', 'description'), array('Naked Triples', '<img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/row_naked_triples.gif" align="left" width="273" height="44" alt="Sudoku naked triples"><p>Another Sudoku tip is to look for "naked triples". Naked triples like the name suggests are three numbers that do not have any other numbers residing in the cells with them. </p><p><p>Unlike naked pairs, naked triples do not need all of the three candidates in every cell. Quite often only two of the three candidates will be shown.</p><p>In the example at the left, the three cells circled are the three naked triples. They are 5,6 and 9. Only a 5,6 and 9 can appear in those three locations. Therefore, you can remove all 5,6, and 9s from the other cells in this row. </p><p>When you remove the 6,9 from two cells and the 5,6 you will discover a naked pair (1,4) and a hidden single (2). See how these Sudoku tips help you solve puzzles?</p>')),
        array_combine(array('name', 'description'), array('Hidden Triples', '<img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/row_hidden_triples.gif" align="left" width="273" height="44" alt="Sudoku hidden triples"><p>Hidden triples are much harder to spot. They will occur in harder puzzles. Hidden triples like naked triples are restricted to three cells in a row, column, or region. Hidden triples like hidden pairs have additional digits that camouflage the three candidates.</p><p><p>If you look at the example at the left, you will see three cells circled in red. These are the hidden triples. Can you spot them?</p><p>You are right, they are 4, 8, and 9. Remove the extra numbers from the cells circled in red. Do you think hidden triples are tough to find? Try quads.</p>')),
        array_combine(array('name', 'description'), array('Naked Quads', '<img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/row_naked_quads.gif" align="left" width="273" height="44" alt="Sudoku naked quads"><p>Another Sudoku tip is to look for "naked quads". Naked quads are like naked triples with the exception that four cells contain only four distinct candidates in a row, column, or region.</p><p>In the example at the left the naked quads are circled. They are 3, 5, 6, and 8. Remove any instance of these four numbers from the other cells in this row.</p>')),
        array_combine(array('name', 'description'), array('Hidden Quads', '<img style="margin:8px 12px 0 0; border:0;" src="http://www.sudokuessentials.com/images/column-hidden-quad.gif" align="left" width="43" height="273" alt="Sudoku hidden quad" title="Sudoku hidden quad"><p>The last of my Sudoku tips for this article is to look for hidden quads. As the name suggest, hidden quads are four cells containing only four distinct candidates in a row, column, or region. These four numbers are hidden by additional candidates. </p><p>Hidden quads are very difficult to find. The good news is I have rarely seen them. (Maybe because they are hidden so well!) They occur only in a few of the more difficult puzzles.</p><p>In my example at the left, the hidden quads circled in red are 1, 5, 6, and 8. It is safe to remove the extra digits (3,4,7,9) from these four cells. </p>')),
    );
    foreach ($records as $record) {
        $DB->insert_record('sudoku_strategy', $record, false);
    }
}

/**
 * Post installation recovery procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_sudoku_install_recovery() {
}
