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

package de.weltraumschaf.ebnf;

import com.google.common.io.Files;
import de.weltraumschaf.ebnf.util.ReaderHelper;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.nio.charset.Charset;

/**
 *
 * @author Sven Strittmatter <weltraumschaf@googlemail.com>
 */
public class TestHelper {

    private static final TestHelper INSTANCE = new TestHelper();

    public static final String FIXTURE_DIR = "/de/weltraumschaf/ebnf";

    private TestHelper() {

    }

    public static TestHelper helper() {
        return INSTANCE;
    }

    public URI createResourceFromFixture(String fixtureFile) throws URISyntaxException {
        return getClass().getResource(FIXTURE_DIR + '/' + fixtureFile).toURI();
    }

    public String createStringFromFixture(String fixtureFile) throws URISyntaxException, IOException {
        return Files.toString(new File(createResourceFromFixture(fixtureFile)), Charset.defaultCharset());
    }

    public BufferedReader createSourceFromFixture(String fixtureFile) throws FileNotFoundException,
                                                                              URISyntaxException {
        return ReaderHelper.createFrom(createResourceFromFixture(fixtureFile));
    }

    public Scanner createScannerFromFixture(String fixtureFile) throws FileNotFoundException, URISyntaxException {
        return new Scanner(createSourceFromFixture(fixtureFile));
    }

    public Parser createParserFromFixture(String fixtureFile) throws FileNotFoundException, URISyntaxException {
        return new Parser(createScannerFromFixture(fixtureFile));
    }
}