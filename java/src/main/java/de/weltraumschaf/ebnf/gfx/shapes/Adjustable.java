/*
 * LICENSE
 *
 * "THE BEER-WARE LICENSE" (Revision 42):
 * "Sven Strittmatter" <weltraumschaf@googlemail.com> wrote this file.
 * As long as you retain this notice you can do whatever you want with
 * this stuff. If we meet some day, and you think this stuff is worth it,
 * you can buy me a beer in return.
 *
 */

package de.weltraumschaf.ebnf.gfx.shapes;

import java.awt.Graphics2D;

/**
 * Implementors can adjust their size and/or position.
 *
 * @author Sven Strittmatter <weltraumschaf@googlemail.com>
 */
public interface Adjustable {

    /**
     * Recalculates the dimension and position of the implementor.
     *
     * Compound adjustables which contains other adjustables may need to invoke
     * this method recursively.
     *
     * @param graphic Graphic context used for font measuring.
     */
    public void adjust(final Graphics2D graphic);

}
