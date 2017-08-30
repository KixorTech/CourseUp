###AABB intersection###

Axis-Aligned Bounding Boxes (AABBs) are a common primitive in ray tracing applications. Their axis aligned properties allow for easily controlled bounds and, along with their box shape, provide very fast intersection algorithms.

AABBs are defined by two terms: a minimum bound and a maximum bound. These bounds are two opposite corners of the box. This is enough to define the layout of the box, since the components can be used to define the six bounding planes of the box. For example, the minimum \(x\) component represents the bounding plane lying in \(yz\) space positions at the \(x\) coordinate on the \\(x\\) axis.
````
min(x, y, z)
max(x, y, z)
````

####Intersections####
Since the AABB is defined by six bounding planes, AABB-ray intersection is a series of 1 dimension line-plane intersections. For each bounding plane, the ray's component is tested against the appropriate AABB bounding plane. This 1D test results in a \(t\) value for the ray intersection that dimension. For a ray position \(r\) and an AABB defined by \(min, max\):

$$
r = e + td
$$

Then, the \(x\) intersect is:
$$
min_x = e_x + td_x
t = \frac{min_x - e_x}{d_x}
$$

Solving all the plane intersections results in six \( t \) parameters (two for each dimension). This parameters represent the positions where the ray passes through the AABB's bounding planes. An intersection occurs when the ray passes through the box bounds. The next step is to then check if the ray enters the \(x\) bound and the \(y\) bound and the \(z) bound before exiting any of them. This is usually implemented by checking of the closest intersect in a dimension is less than the farthest intersect in the other two dimensions.

####Normals####
AABB normals can be computed programmatically. This is easily detected during intersection. Based on the plane that causes the intersection, an axis-aligned vector can be returned. For example, intersecting the minimum X plane would result in a normal of \( (0, -1, 0) \). The other normals are computed similarly.

The normal can also be computed after intersection from the hitpoint. The hitpoint should lie on one of the bounding planes of the box. So, checking the hitpoint components against the appropriate planes will tell which plane caused the intersection. For example, if the hitpoint was caused by the \( -x \) plane, the hitpoint's \(x\) component should match on the minimum \(x\) bound of the box. Be sure to account for floating point precision issues with some kind of error epsilon in your code.


