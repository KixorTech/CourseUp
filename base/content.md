#Math Mode Guards
**Tyson Clark and Olivia Penry**

There are three ways to indicate math modes: 

\\\(option 1, inline\\\)

/$option 2, inline/$ and

/$$

option 3, block

/$$

In the parsing process, PDExtension runs the code first through Markdown then LaTeX. If any Markdown-recognized formatting guards are withn in the LaTeX math mode guards, Markdown processes the formatting and thus the LaTeX code is compromised. 

To turn LaTeX parsing off, comment out the only line in the function requireLatex() in helpers.php, currently line 96.

##Blocked, double dollar sign
Having it inline can work (see content.md for code): $$\int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx$$
but shouldn't work. Having it all on its own line causes formatting errors in following lines:
$$\int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx $$
as you can see in this regular text.
$$ (this extra guard should not be necessary)
The correct usage is using double dollar signs at the beginning and end of the LaTeX code on separate lines:

$$
\mathbf{M}_{projection}=
\begin{bmatrix}
\frac{2}{r-l} & 0 & 0 & -\frac{r+l}{r-l} \\\\
0 & \frac{2}{t-b} & 0 & -\frac{t+b}{t-b} \\\\
0 & 0 & \frac{2}{n-f} & -\frac{n+f}{n-f} \\\\
0 & 0 & 0 & 1
\end{bmatrix}
$$

This is how block mode worked before and we did not change anything. Overall, as long as it is used correctly, it works fine from what we can tell.

##Inline mode, single dollar sign
We think it may be useful to do a preg_split of the markup by using a regex with /$ and [^////], which indicates any character other than a backslash. Some ideas may be to use '[^\$////]\$'. The issue we ran into is that you have to ignore double dollar signs but [^\$\$] did not work.  

Someone writing the content.md file should indicate a regular dollar sign by using a backslash in front of it, as the math mode guards is defined by a single dollar sign. The preg_split separates the string by the regex, so anything in the returned array with an odd index should be parsed in LaTeX. 

For example, the line

**Writing things before /$ x\cdot y\times z /$ and after LaTeX**

would be separated into ["Writing things before", "x\cdot y\times z", "and after LaTeX"]. The odd indexed item(s) should be shoehorned into the string after Markdown parsing occurs on the even-indexed items. 

Uncomment the first lines in the parseInput() function of PDExtension (line 68 currently) to test out some of our regex attempts.

Example of single dollar sign math modes: text before $x\cdot y\times z$ and $\int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx$ after

##Inline mode, backslash and ()s
This works the same as it did before and faces the same parsing formatting errors as mentioned previously.

Example: Some text before \(x\cdot y\times z\) and \( \int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx \) and \(
\mathbf{M}_{projection}=
\begin{bmatrix}
\frac{2}{r-l} & 0 & 0 & -\frac{r+l}{r-l} \\\\
0 & \frac{2}{t-b} & 0 & -\frac{t+b}{t-b} \\\\
0 & 0 & \frac{2}{n-f} & -\frac{n+f}{n-f} \\\\
0 & 0 & 0 & 1
\end{bmatrix}
\)
after. A portion of LaTeX is italicized as it is between underscores, _which MarkDown recognizes as a styling guard._
