#Math Mode Guards
**Tyson Clark and Olivia Penry**

There are three ways to indicate math modes: 

\\\(option 1, inline\\\)

/$option 2, inline/$ and

/$$

option 3, block

/$$

Beforehand, the double $$ was working but the two inline guards were not. In the parsing process, PDExtension ran the code first through Markdown then LaTeX. If any Markdown-recognized formatting guards were withn in the LaTeX math mode guards, Markdown would process the formatting and thus the LaTeX code was compromised. 

The partial solution we found is to preg_split the markup by using a regex with /$ and [^////], which indicates any character other than a backslash. Someone writing the content.md file needs to indicate a regular dollar sign with a backslash in front of it, as the math mode guards is defined by a single dollar sign. The preg_split separates the string by the regex, so anything in the returned array with an odd index should be parsed in LaTeX. 

For example, the line

**Writing things before /$ x\cdot y\times z /$ and after LaTeX**

would be separated into ["Writing things before ", x\cdot y\times z, "and after LaTeX]. The odd indexed item(s) should be shoehorned into the string after Markdown parsing occurs on the even-indexed items. 

To turn LaTeX parsing off, comment out the only line in the function requireLatex() in helpers.php, currently line 96

##Blocked, double dollar sign
Having it inline does work (see content.md for code): $$\int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx$$
but shouldn't work. Having it separately causes formatting errors:
$$\int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx $$
as you can see in this regular text.
$$ (this extra guard should not be necessary)
Using double dollar signs at the beginning and end of the LaTeX code on separate lines works:

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

Some text before $x\cdot y\times z$ and $\int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx$
and
<!-- $
\mathbf{M}_{projection}=
\begin{bmatrix}
\frac{2}{r-l} & 0 & 0 & -\frac{r+l}{r-l} \\\\
0 & \frac{2}{t-b} & 0 & -\frac{t+b}{t-b} \\\\
0 & 0 & \frac{2}{n-f} & -\frac{n+f}{n-f} \\\\
0 & 0 & 0 & 1
\end{bmatrix}
$ -->
after.

##Inline mode, \\(, \\)

Some text before \(x\cdot y\times z\) and \( \int_{-\infty}^\infty\frac{x}{\sqrt{x^2}}dx \) and \(
\mathbf{M}_{projection}=
\begin{bmatrix}
\frac{2}{r-l} & 0 & 0 & -\frac{r+l}{r-l} \\\\
0 & \frac{2}{t-b} & 0 & -\frac{t+b}{t-b} \\\\
0 & 0 & \frac{2}{n-f} & -\frac{n+f}{n-f} \\\\
0 & 0 & 0 & 1
\end{bmatrix}
\)
after
