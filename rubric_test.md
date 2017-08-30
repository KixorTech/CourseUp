###Program - Ray tracer###

Develop and implement your own ray tracer. Your ray tracer should be a stand-alone piece of software - it should require a file with a scene in order to render something. As a minimum, your ray tracer must provide the following functionality:

* Multiple point light sources
* Triangles and sphere primitives
* Surface shading
* Shadows
* Reflections
* Acceleration structures

Your ray tracer will be developed in three stages. You must complete each stage before moving on to the next. You should submit your code to your course repository at <a href="http://svn.csse.rose-hulman.edu/repos/1516c-csse451-username">http://svn.csse.rose-hulman.edu/repos/1516c-csse451-<em>username</em></a>. All source files must be in the root directory of your repository and there can be no unused source files. I will use a makefile similar to the one in the example code directory to build your project. Please make sure your code compiles correctly with only the standard C++ 11 libraries (testing your builds on abacus may help).

At the end of each week, I will review your progress in class.

<div class="pagebreak"></div>
<!--? printHWAuthorBlanks(); ?-->


####Stage 1 (50) ####

RUBRIC
OBJ model loading (5) : No OBJ loading (0), Can load OBJ models (5)
Arbitrary camera positions (15) : No camera (0), Correct camera setup (5), Arbitrary camera position and orientation (15)
Sphere intersection (10) : Partially correct (3), Generalized correct sphere-ray intersection (10)
Triangle intersection (15) : Incomplete or incorrect (0) Generalized correct triangle-ray intersection (15)
Framebuffer output (5) : Incomplete or incorrect (0) Can output framebuffer to storage (5)

####Stage 2 (50)####
Support the following:
* Materials (5)
* Point light (10)
* Ambient shading (2)
* Diffuse shading (3)
* Phong shading (5)
* Shadows (10)
* Reflections (15)

####Stage 3 (50) ####
* Support the following:
* AABB primitives (5)
* Slab intersections (5)
* Object or spatial median splitting (10)
* BVH tree creation (20)
* BVH tree traversal (10)

