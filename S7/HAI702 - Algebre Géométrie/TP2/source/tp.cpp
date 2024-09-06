// -------------------------------------------
// gMini : a minimal OpenGL/GLUT application
// for 3D graphics.
// Copyright (C) 2006-2008 Tamy Boubekeur
// All rights reserved.
// -------------------------------------------

// -------------------------------------------
// Disclaimer: this code is dirty in the
// meaning that there is no attention paid to
// proper class attribute access, memory
// management or optimisation of any kind. It
// is designed for quick-and-dirty testing
// purpose.
// -------------------------------------------

#include <iostream>
#include <fstream>
#include <vector>
#include <algorithm>
#include <string>
#include <cstdio>
#include <cstdlib>

#include <algorithm>
#include <GL/glut.h>
#include <float.h>
#include "src/Vec3.h"
#include "src/Triangle.h"
#include "src/Mesh.h"
#include "src/MeshIO.h"
#include "src/Camera.h"


enum DisplayMode {
    WIRE = 0, SOLID = 1, LIGHTED_WIRE = 2, LIGHTED = 3
};

//Transformation made of a matrix multiplication and translation
struct Transform {

protected:
    Mat3 m_transformation; //Includes rotation + scale
    Mat3 m_vector_transformation; //Transformation matrix for vectors
    Vec3 m_translation; //translation applied to points
public:

    //Constructor, by default Identity and no translation
    Transform(Mat3 i_transformation = Mat3::Identity(), Vec3 i_translation = Vec3(0., 0., 0.))
            : m_transformation(i_transformation), m_translation(i_translation) {
        // Question 1.3: TODO, modifier la ligne suivante pour que transformation m_vector_transformation soit la bonne transformation à appliquer aux vecteurs normaux.
        m_vector_transformation = (m_transformation.getTranspose()).inverse(m_transformation.getTranspose());

    }

    //Fonction pour appliquer la transformation à un point Vec3
    Vec3 apply_to_point(Vec3 const &i_position) {
        return m_transformation * i_position + m_translation;
    }

    //Transformation à appliquer à un vecteur
    Vec3 apply_to_vector(Vec3 const &k_vector) {
        return m_vector_transformation * k_vector;
    }

    //Transformation appliquer à un vecteur normalisé : example une normale
    Vec3 apply_to_normalized_vector(Vec3 const &k_vector) {
        Vec3 result = m_vector_transformation * k_vector;
        //Question 1.4: TODO, compléter
        //result.normalize();
        return result;
    }

    Vec3 const &translation() { return m_translation; }

    Mat3 const &transformation_matrix() { return m_transformation; }

    Mat3 const &vector_transformation_matrix() { return m_vector_transformation; }
};

//Basis ( origin, i, j ,k )
struct Basis {
    inline Basis(Vec3 const &i_origin, Vec3 const &i_i, Vec3 const &i_j, Vec3 const &i_k) {
        m_origin = i_origin;
        m_i = i_i;
        m_j = i_j;
        m_k = i_k;
    }

    inline Basis() {
        m_origin = Vec3(0., 0., 0.);
        m_i = Vec3(1., 0., 0.);
        m_j = Vec3(0., 1., 0.);
        m_k = Vec3(0., 0., 1.);
    }

    Vec3 operator[](unsigned int ib) {
        if (ib == 0) return m_i;
        if (ib == 1) return m_j;
        return m_k;
    }

    void set_axis(unsigned int v, Vec3 axis) {
        if (v == 0) m_i = axis;
        if (v == 1) m_j = axis;
        if (v == 2) m_k = axis;
    }

    Vec3 axis(unsigned int ib) {
        if (ib == 0) return m_i;
        if (ib == 1) return m_j;
        return m_k;
    }

    //Normalized axis of the basis
    Vec3 normalized_axis(unsigned int ib) {
        if (ib == 0) return normalized_i();
        if (ib == 1) return normalized_j();
        return normalized_k();
    }

    Vec3 origin() { return m_origin; }

    Vec3 i() { return m_i; }

    Vec3 j() { return m_j; }

    Vec3 k() { return m_k; }

    Vec3 normalized_i() { return m_i / m_i.length(); }

    Vec3 normalized_j() { return m_j / m_j.length(); }

    Vec3 normalized_k() { return m_k / m_k.length(); }

protected:
    Vec3 m_origin;
    Vec3 m_i;
    Vec3 m_j;
    Vec3 m_k;
};

struct Plane {
    inline Plane(Vec3 const &i_point, Vec3 const &i_normal) {
        point = i_point;
        normal = i_normal;
    }

    inline Plane() {
        point = Vec3(0., 0., 0.);
        normal = Vec3(0., 1., 0.);
    }

    Vec3 point;
    Vec3 normal;
};

//Input mesh loaded at the launch of the application
Mesh mesh;
//Mesh on which a transformation is applied
Mesh transformed_mesh;

Basis basis;
Basis transformed_basis;

Mesh ellipsoide;
Mesh transformed_ellipsoide;

Plane planes[3];
Plane transformed_planes[3];

bool display_normals;
bool display_smooth_normals;
bool display_mesh;
bool display_transformed_mesh;
bool display_basis;
bool display_transformed_basis;
bool display_ellipsoide;
bool display_plane;

unsigned int plane_id;

DisplayMode displayMode;
// -------------------------------------------
// OpenGL/GLUT application code.
// -------------------------------------------

static GLint window;
static unsigned int SCREENWIDTH = 1600;
static unsigned int SCREENHEIGHT = 900;
static Camera camera;
static bool mouseRotatePressed = false;
static bool mouseMovePressed = false;
static bool mouseZoomPressed = false;
static int lastX = 0, lastY = 0, lastZoom = 0;
static bool fullScreen = false;

// ----------------------------------------------------
// Projections sur des plans et des droites
// ----------------------------------------------------

//Calcul de la projection d'un point sur un plan
Vec3 project(Vec3 const &input_point, Plane const &i_plane) {
    Vec3 result = input_point;
    //Question 2.3: TODO, projeter input_point sur le plan i_plane
    return result;
}

// Calcul de la projection d'un point sur une droite (définie par un vecteur et un point)
Vec3 project(Vec3 const &input_point, Vec3 const &i_origin, Vec3 const &i_axis) {
    Vec3 result = input_point;
    //Question 2.3: TODO, projeter input_point sur l'axe
    return result;
}

// Calcul de la projection d'une liste de points sur une droite (définie par un vecteur et un point)
void project(std::vector < Vec3 > const & i_points,
             std::vector <Vec3> &o_points,
             Vec3 const & i_origin,
             Vec3 const &i_axis) {
    o_points.clear();

    for( unsigned int i = 0; i < i_points.size(); i++ )
        o_points.push_back( project(i_points[i], i_origin, i_axis));
}

// Calcul de la projection d'une liste de points sur un plan
void project(std::vector < Vec3 > const & i_points,
             std::vector <Vec3> &o_points, Plane
             const & i_plane ) {
    o_points.clear();

    for( unsigned int i = 0; i<i_points.size(); i++ )
        o_points.push_back ( project(i_points[i], i_plane));

}


//----------------------------------------------------------
// PCA: Compute the principle axis of the input shape
//----------------------------------------------------------

// Question 2.1: TODO, expliquer le code suivant
void compute_principal_axis(std::vector < Vec3 > const & ps,
                            Vec3 &eigenvalues,
                            Vec3 &first_eigenvector,
                            Vec3 &second_eigenvector,
                            Vec3 &third_eigenvector ) {
    
    Vec3 p(0., 0., 0.);
    for(unsigned int i=0; i<ps.size(); ++i)
        p += ps[i];
    p /= (float)ps.size();

    Mat3 C(0, 0, 0, 0, 0, 0, 0, 0, 0);
    for( unsigned int i=0; i<ps.size(); ++i ) {
        Vec3 const& pi = ps[i];
        C += Mat3::tensor( pi - p, pi - p );
    }
    C = (1./ps.size()) * C;

    C.diagonalize(eigenvalues, first_eigenvector, second_eigenvector, third_eigenvector);
}


//----------------------------------------------------------
// SVD: find the transformation with exact correspondances
//----------------------------------------------------------

// Question 3.1: TODO, expliquer le code suivant
void find_transformation_SVD(std::vector < Vec3 > const &ps,
                             std::vector <Vec3> const &qs,
                             Mat3 &rotation,
                             Vec3 &translation ) {

    Vec3 p(0, 0, 0), q(0, 0, 0);
    for( unsigned int i=0; i<ps.size(); ++i ) {
        const Vec3 &pi = ps[i];
        const Vec3 &qi = qs[i];

        p += pi;
        q += qi;
    }
    p /= (float)ps.size();
    q /= (float)qs.size();

    Mat3 C(0, 0, 0, 0, 0, 0, 0, 0, 0);
    for(unsigned int i = 0; i<ps.size(); ++i ) {
        const Vec3 &pi = ps[i];
        const Vec3 &qi = qs[i];
        C += Mat3::tensor( qi - q, pi - p );
    }
    
    //Calcul de la rotation grace à la SVD
    C.setRotation();

    //Retourner la rotation
    rotation = C;
    //et la translation calculée
    translation = q - rotation * p;
}

//Mise à jour des données transformées (maillage, plan, base et ellipsoide)
void update_transformed(Transform i_transform) {

    for (unsigned int i = 0; i < transformed_mesh.vertices.size(); i++) {
        transformed_mesh.vertices[i] = i_transform.apply_to_point(transformed_mesh.vertices[i]);
        transformed_mesh.normals[i] = i_transform.apply_to_normalized_vector(transformed_mesh.normals[i]);
    }

    //Mise à jour des normales aux triangles du maillage gris (i.e. le maillage transformé)
    for (unsigned int i = 0; i < transformed_mesh.triangles.size(); ++i) {
        transformed_mesh.triangle_normals[i] = i_transform.apply_to_normalized_vector(
                transformed_mesh.triangle_normals[i]);
    }

    //Calcul de la nouvelle base (transformation des axes en utilisant la matrice de transformation des directions)
    transformed_basis = Basis(i_transform.apply_to_point(transformed_basis.origin()),
                              i_transform.apply_to_vector(transformed_basis.i()),
                              i_transform.apply_to_vector(transformed_basis.j()),
                              i_transform.apply_to_vector(transformed_basis.k()));

    //Mise à jour des plans
    for (unsigned int i = 0; i < 3; i++)
        transformed_planes[i] = Plane(transformed_basis.origin(), transformed_basis.normalized_axis(i));

    //Mise à jour de l'ellispoide
    for (unsigned int i = 0; i < transformed_ellipsoide.vertices.size(); ++i) {
        transformed_ellipsoide.vertices[i] = i_transform.apply_to_point(transformed_ellipsoide.vertices[i]);

        transformed_ellipsoide.normals[i] = i_transform.apply_to_normalized_vector(transformed_ellipsoide.normals[i]);
    }

    for (unsigned int i = 0; i < transformed_ellipsoide.triangles.size(); ++i) {
        transformed_ellipsoide.triangle_normals[i] = i_transform.apply_to_normalized_vector( transformed_ellipsoide.triangle_normals[i]);
    }

}

//Calcul de la trasformation entre maillage initial et le maillage placé grace à transform
void compute_transform() {
    Mat3 rotation;
    Vec3 translation;
    //Calcul de la transformation à appliquer grace à la SVD
    find_transformation_SVD(transformed_mesh.vertices, mesh.vertices, rotation, translation);

    //Mise à jour des données en utilisant l'objet transform calculé
    Transform transform(rotation, translation);
    update_transformed(transform);
}


// ---------------------------------------------------------------------------------------------
// Création d'un ellipsoide avec une transformation permettant de centrer sur la forme et de l'adapter aux échelles suivants les directions principales
// ---------------------------------------------------------------------------------------------
void setEllipsoide(Mesh &o_mesh, Transform i_transform, int nX = 20, int nY = 20) {
    o_mesh.vertices.clear();
    o_mesh.normals.clear();
    o_mesh.triangles.clear();

    float thetaStep = 2 * M_PI / (nX - 1);
    float phiStep = M_PI / (nY - 1);

    for (int i = 0; i < nX; i++) {
        for (int j = 0; j < nY; j++) {
            float t = thetaStep * i;
            float p = phiStep * j - M_PI / 2;

            Vec3 position(cos(t) * cos(p), sin(t) * cos(p), sin(p));
            //Application de la transformation aux sommets
            o_mesh.vertices.push_back(i_transform.apply_to_point(position));
            //Application de la transformation aux normales
            o_mesh.normals.push_back(i_transform.apply_to_normalized_vector(position));
        }
    }
    for (int i = 0; i < nX - 1; i++) {
        for (int j = 0; j < nY - 1; j++) {
            o_mesh.triangles.push_back(Triangle(i * nY + j, (i + 1) * nY + j + 1, (i + 1) * nY + j));
            o_mesh.triangles.push_back(Triangle(i * nY + j, i * nY + j + 1, (i + 1) * nY + j + 1));
        }
    }

    ellipsoide.computeTrianglesNormals();
}

void setEllipsoide( Mesh &o_mesh, Vec3 i_origin, float i_sqrt_factor, Vec3 i_eigenvalues, Vec3 i_eigenvector_1, Vec3 i_eigenvector_2, Vec3 i_eigenvector_3, int nX = 20, int nY = 20) {


    //Construire une base en utilisant le resultat de l'ACP
    //Centroid : origine de la base
    //Axes : directions principales i.e. vecteur propres
    //Echelle des axes : facteur*racine carrée des valeur propres associées aux axes
    Basis ellispoide_basis(i_origin,
                           i_sqrt_factor * sqrt(i_eigenvalues[0]) * i_eigenvector_1,
                           i_sqrt_factor * sqrt(i_eigenvalues[1]) * i_eigenvector_2,
                           i_sqrt_factor * sqrt(i_eigenvalues[2]) * i_eigenvector_3);

    //Utiliser cette base pour définir une matrice de transformation
    Mat3 transformation_matrix = Mat3::getFromCols(ellispoide_basis.i(), ellispoide_basis.j(), ellispoide_basis.k());
    //Définir un objet transform
    Transform transform(transformation_matrix, i_origin);

    //Créer l'ellipsoide correspondante centrée sur le maillage dont la formest est adaptée à l'objet
    setEllipsoide(ellipsoide, transform);

}

// ------------------------------------
// Application initialization
// ------------------------------------
void initLight() {
    GLfloat light_position1[4] = {22.0f, 16.0f, 50.0f, 0.0f};
    GLfloat direction1[3] = {-52.0f, -16.0f, -50.0f};
    GLfloat color1[4] = {1.0f, 1.0f, 1.0f, 1.0f};
    GLfloat ambient[4] = {0.3f, 0.3f, 0.3f, 0.5f};

    glLightfv(GL_LIGHT1, GL_POSITION, light_position1);
    glLightfv(GL_LIGHT1, GL_SPOT_DIRECTION, direction1);
    glLightfv(GL_LIGHT1, GL_DIFFUSE, color1);
    glLightfv(GL_LIGHT1, GL_SPECULAR, color1);
    glLightModelfv(GL_LIGHT_MODEL_AMBIENT, ambient);
    glEnable(GL_LIGHT1);
    glEnable(GL_LIGHTING);
}

void init() {
    camera.resize(SCREENWIDTH, SCREENHEIGHT);
    initLight();
    glCullFace(GL_BACK);
    glDisable(GL_CULL_FACE);
    glDepthFunc(GL_LESS);
    glEnable(GL_DEPTH_TEST);
    glClearColor(0.2f, 0.2f, 0.3f, 1.0f);
    glEnable(GL_COLOR_MATERIAL);
    glLightModeli(GL_LIGHT_MODEL_TWO_SIDE, GL_TRUE);

    display_normals = false;
    display_smooth_normals = false;
    display_mesh = true;
    display_transformed_mesh = true;
    display_basis = false;
    display_transformed_basis = false;
    display_ellipsoide = false;
    display_plane = false;
    displayMode = LIGHTED;

    plane_id = 0;
}


// ------------------------------------
// Rendering.
// ------------------------------------

void drawPointSet(std::vector < Vec3 >
                  const & i_positions  ) {
    glDisable(GL_LIGHTING);
    glPointSize(4);
    glBegin(GL_POINTS);
    for( unsigned int pIt = 0; pIt<i_positions.size(); ++pIt ) {
        glVertex3f( i_positions[pIt][0], i_positions[pIt][1], i_positions[pIt][2] );
    }

    glEnd();

    glEnable(GL_LIGHTING);
}


void drawVector(Vec3 const &i_from, Vec3 const &i_to) {

    glBegin(GL_LINES);
    glVertex3f(i_from[0], i_from[1], i_from[2]);
    glVertex3f(i_to[0], i_to[1], i_to[2]);
    glEnd();
}

void drawAxis(Vec3 const &i_origin, Vec3 const &i_direction) {

    glLineWidth(4); // for example...
    drawVector(i_origin, i_origin + i_direction);
    glLineWidth(1); // for example...
}

void drawReferenceFrame(Vec3 const &origin, Vec3 const &i, Vec3 const &j, Vec3 const &k) {

    glDisable(GL_LIGHTING);
    glColor3f(0.8, 0.2, 0.2);
    drawAxis(origin, i);
    glColor3f(0.2, 0.8, 0.2);
    drawAxis(origin, j);
    glColor3f(0.2, 0.2, 0.8);
    drawAxis(origin, k);
    glEnable(GL_LIGHTING);

}

void drawReferenceFrame(Basis &i_basis) {
    drawReferenceFrame(i_basis.origin(), i_basis.i(), i_basis.j(), i_basis.k());
}

void drawPlane(Plane const &i_plane) {


    Vec3 j = i_plane.normal;
    Vec3 i = i_plane.normal.getOrthogonal();
    i.normalize();
    Vec3 k = Vec3::cross(i, j);

    Mat3 tr = Mat3::getFromCols(i, j, k);

    Vec3 iplanes_vertices[] = {
        tr * Vec3(-1., 0., 1.) + i_plane.point,
        tr * Vec3(1., 0., 1.) + i_plane.point,
        tr * Vec3(1., 0., -1.) + i_plane.point,
        tr * Vec3(-1., 0., -1.) + i_plane.point
    };

    glBegin(GL_QUADS);
    glNormal3f(i_plane.normal[0], i_plane.normal[1], i_plane.normal[2]);
    for (unsigned int i = 0; i < 4; i++)
        glVertex3f(iplanes_vertices[i][0], iplanes_vertices[i][1], iplanes_vertices[i][2]);
    glEnd();

    Vec3 to = 0.5 * i_plane.normal;
    glColor3f(0.8, 1, 0.5); // GREEN
    drawAxis(i_plane.point, to);
}


//Fonction de dessin en utilisant les normales au sommet
void drawSmoothTriangleMesh(Mesh const &i_mesh) {
    glBegin(GL_TRIANGLES); //Fonction OpenGL de dessin de triangles
    for (unsigned int tIt = 0; tIt < i_mesh.triangles.size(); ++tIt) {
        Vec3 p0 = i_mesh.vertices[i_mesh.triangles[tIt][0]];
        Vec3 n0 = i_mesh.normals[i_mesh.triangles[tIt][0]];

        Vec3 p1 = i_mesh.vertices[i_mesh.triangles[tIt][1]];
        Vec3 n1 = i_mesh.normals[i_mesh.triangles[tIt][1]];

        Vec3 p2 = i_mesh.vertices[i_mesh.triangles[tIt][2]];
        Vec3 n2 = i_mesh.normals[i_mesh.triangles[tIt][2]];

        //Dessin des trois sommets formant le triangle
        glNormal3f(n0[0], n0[1], n0[2]);
        glVertex3f(p0[0], p0[1], p0[2]);
        glNormal3f(n1[0], n1[1], n1[2]);
        glVertex3f(p1[0], p1[1], p1[2]);
        glNormal3f(n2[0], n2[1], n2[2]);
        glVertex3f(p2[0], p2[1], p2[2]);
    }
    glEnd();

}

//Fonction de dessin en utilisant les normales au triangles
void drawTriangleMesh(Mesh const &i_mesh) {
    glBegin(GL_TRIANGLES);
    for (unsigned int tIt = 0; tIt < i_mesh.triangles.size(); ++tIt) {
        Vec3 p0 = i_mesh.vertices[i_mesh.triangles[tIt][0]];
        Vec3 p1 = i_mesh.vertices[i_mesh.triangles[tIt][1]];
        Vec3 p2 = i_mesh.vertices[i_mesh.triangles[tIt][2]];

        //Face normal
        Vec3 n = i_mesh.triangle_normals[tIt];

        glNormal3f(n[0], n[1], n[2]);

        glVertex3f(p0[0], p0[1], p0[2]);
        glVertex3f(p1[0], p1[1], p1[2]);
        glVertex3f(p2[0], p2[1], p2[2]);
    }
    glEnd();

}

void drawMesh(Mesh const &i_mesh) {
    if (display_smooth_normals)
        drawSmoothTriangleMesh(i_mesh); //Smooth display with vertices normals
    else
        drawTriangleMesh(i_mesh); //Display with face normals
}

void drawVectorField(std::vector < Vec3 >
                     const & i_positions,
                     std::vector <Vec3> const &i_directions
                     ) {
    glLineWidth(1.);
    for( unsigned int pIt = 0; pIt<i_directions.size(); ++pIt ) {
        Vec3 to = i_positions[pIt] + 0.02 * i_directions[pIt];
        drawVector(i_positions[pIt], to );
    }
}

void drawNormals(Mesh const &i_mesh) {

    if (display_smooth_normals) {
        drawVectorField(i_mesh.vertices, i_mesh.normals);
    } else {
        std::vector <Vec3> triangle_baricenters;
        for (const Triangle &triangle: i_mesh.triangles) {
            Vec3 triangle_baricenter(0., 0., 0.);
            for (unsigned int i = 0; i < 3; i++)
                triangle_baricenter += i_mesh.vertices[triangle[i]];
            triangle_baricenter /= 3.;
            triangle_baricenters.push_back(triangle_baricenter);
        }

        drawVectorField(triangle_baricenters, i_mesh.triangle_normals);
    }
}

//Draw fonction
void draw() {

    if (displayMode == LIGHTED || displayMode == LIGHTED_WIRE) {

        glPolygonMode(GL_FRONT_AND_BACK, GL_FILL);
        glEnable(GL_LIGHTING);

    } else if (displayMode == WIRE) {

        glPolygonMode(GL_FRONT_AND_BACK, GL_LINE);
        glDisable(GL_LIGHTING);

    } else if (displayMode == SOLID) {
        glDisable(GL_LIGHTING);
        glPolygonMode(GL_FRONT_AND_BACK, GL_FILL);

    }

    if (display_mesh) {
        glColor3f(0.8, 1, 0.8);
        drawMesh(mesh);
        if (display_ellipsoide) {
            glColor3f(0.39, 0.57, 0.67);
            drawMesh(ellipsoide);
        }
    }
    if (display_transformed_mesh) {
        glColor3f(0.8, 0.8, 1);
        drawMesh(transformed_mesh);
        if (display_ellipsoide) {
            glColor3f(0.39, 0.57, 0.67);
            drawMesh(transformed_ellipsoide);
        }
    }

    if (displayMode == SOLID || displayMode == LIGHTED_WIRE) {
        glEnable(GL_POLYGON_OFFSET_LINE);
        glPolygonMode(GL_FRONT_AND_BACK, GL_LINE);
        glLineWidth(1.0f);
        glPolygonOffset(-2.0, 1.0);

        glColor3f(0., 0., 0.);
        if (display_mesh) {
            drawMesh(mesh);
            if (display_ellipsoide) drawMesh(ellipsoide);
        }
        if (display_transformed_mesh) {
            drawMesh(transformed_mesh);
            if (display_ellipsoide) drawMesh(transformed_ellipsoide);
        }

        glDisable(GL_POLYGON_OFFSET_LINE);
        glEnable(GL_LIGHTING);
    }

    glDisable(GL_LIGHTING);
    if (display_normals) {
        glColor3f(1., 0., 0.);
        if (display_mesh) {
            drawNormals(mesh);
            if (display_ellipsoide) drawNormals(ellipsoide);
        }
        if (display_transformed_mesh) {
            drawNormals(transformed_mesh);
            if (display_ellipsoide) drawNormals(transformed_ellipsoide);
        }
    }

    if (display_basis) {

        if (display_mesh) drawReferenceFrame(basis);

        glColor3f(1., 1, 1.);
        if (display_mesh) {
            std::vector <Vec3> projection_on_basis;
            project(mesh.vertices, projection_on_basis, basis.origin(), basis.normalized_axis(plane_id));
            drawPointSet(projection_on_basis);
        }

    }

    if (display_transformed_basis) {
        if (display_transformed_mesh) drawReferenceFrame(transformed_basis);

        /*glColor3f(1., 1, 1.);
        if (display_transformed_mesh) {
        std::vector <Vec3> transformed_projection_on_basis;
        project(transformed_mesh.vertices, transformed_projection_on_basis, transformed_basis.origin(),
                transformed_basis.normalized_axis(plane_id));
        drawPointSet(transformed_projection_on_basis);
    }*/
    }

    if (display_plane) {
        glColor3f(0.94, 0.81, 0.38);
        if (display_mesh) drawPlane(planes[plane_id]);
        //if (display_transformed_mesh) drawPlane(transformed_planes[plane_id]);
        glColor3f(1., 1, 1.);
        if (display_mesh) {
            std::vector <Vec3> projection_on_plane;
            project(mesh.vertices, projection_on_plane, planes[plane_id]);
            drawPointSet(projection_on_plane);
        }
        /*if (display_transformed_mesh) {
            std::vector <Vec3> transformed_projection_on_plane;
            project(transformed_mesh.vertices, transformed_projection_on_plane, transformed_planes[plane_id]);
            drawPointSet(transformed_projection_on_plane);
        }*/
    }

    glEnable(GL_LIGHTING);

}

void changeDisplayMode() {
    if (displayMode == LIGHTED)
        displayMode = LIGHTED_WIRE;
    else if (displayMode == LIGHTED_WIRE)
        displayMode = SOLID;
    else if (displayMode == SOLID)
        displayMode = WIRE;
    else
        displayMode = LIGHTED;
}

void display() {
    glLoadIdentity();
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);
    camera.apply();
    draw();
    glFlush();
    glutSwapBuffers();
}

void idle() {
    glutPostRedisplay();
}

// ------------------------------------
// User inputs
// ------------------------------------
//Keyboard event
void key(unsigned char keyPressed, int x, int y) {
    switch (keyPressed) {
        case 'f':
            if (fullScreen == true) {
                glutReshapeWindow(SCREENWIDTH, SCREENHEIGHT);
                fullScreen = false;
            } else {
                glutFullScreen();
                fullScreen = true;
            }
            break;


        case 'w': //Change le mode d'affichage
            changeDisplayMode();
            break;

        case 'b': //Toggle basis display
            display_basis = !display_basis;
            break;

        case 'n': //Press n key to display normals
            display_normals = !display_normals;
            break;

        case '1': //Toggle loaded mesh display
            display_mesh = !display_mesh;
            break;

        case '2': //Toggle transformed mesh display
            display_transformed_mesh = !display_transformed_mesh;
            break;

        case 's': //Switches between face normals and vertices normals
            display_smooth_normals = !display_smooth_normals;
            break;
        case 't': //Computes the transformation between mesh and mesh_transform using an SVD
            compute_transform();
            break;

        case 'p': //Toggle plane display
            display_plane = !display_plane;
            break;

        case '+': //change plane to display
            plane_id++;
            if (plane_id > 2) plane_id = 0;
            break;

        case 'e': //Toggle ellipsoide display
            display_ellipsoide = !display_ellipsoide;
            break;

        case 'z': //Toggle transformed basis display
            display_transformed_basis = !display_transformed_basis;
            break;

        default:
            break;
    }
    idle();
}

//Mouse events
void mouse(int button, int state, int x, int y) {
    if (state == GLUT_UP) {
        mouseMovePressed = false;
        mouseRotatePressed = false;
        mouseZoomPressed = false;
    } else {
        if (button == GLUT_LEFT_BUTTON) {
            camera.beginRotate(x, y);
            mouseMovePressed = false;
            mouseRotatePressed = true;
            mouseZoomPressed = false;
        } else if (button == GLUT_RIGHT_BUTTON) {
            lastX = x;
            lastY = y;
            mouseMovePressed = true;
            mouseRotatePressed = false;
            mouseZoomPressed = false;
        } else if (button == GLUT_MIDDLE_BUTTON) {
            if (mouseZoomPressed == false) {
                lastZoom = y;
                mouseMovePressed = false;
                mouseRotatePressed = false;
                mouseZoomPressed = true;
            }
        }
    }

    idle();
}

//Mouse motion, update camera
void motion(int x, int y) {
    if (mouseRotatePressed == true) {
        camera.rotate(x, y);
    } else if (mouseMovePressed == true) {
        camera.move((x - lastX) / static_cast<float>(SCREENWIDTH), (lastY - y) / static_cast<float>(SCREENHEIGHT), 0.0);
        lastX = x;
        lastY = y;
    } else if (mouseZoomPressed == true) {
        camera.zoom(float(y - lastZoom) / SCREENHEIGHT);
        lastZoom = y;
    }
}


void reshape(int w, int h) {
    camera.resize(w, h);
}

// ------------------------------------
// Start of graphical application
// ------------------------------------
int main(int argc, char **argv) {
    if (argc > 2) {
        exit(EXIT_FAILURE);
    }
    glutInit(&argc, argv);
    glutInitDisplayMode(GLUT_RGBA | GLUT_DEPTH | GLUT_DOUBLE);
    glutInitWindowSize(SCREENWIDTH, SCREENHEIGHT);
    window = glutCreateWindow("TP HAI702I");

    init();
    glutIdleFunc(idle);
    glutDisplayFunc(display);
    glutKeyboardFunc(key);
    glutReshapeFunc(reshape);
    glutMotionFunc(motion);
    glutMouseFunc(mouse);
    key('?', 0, 0);

    //Mesh loaded with precomputed normals
    openOFF("data/elephant_n.off", mesh.vertices, mesh.normals, mesh.triangles, mesh.triangle_normals);

    //Calcul le centroid du maillage
    Vec3 centroid(0.f, 0.f, 0.f);
    for (unsigned int v = 0; v < mesh.vertices.size(); ++v)
        centroid += mesh.vertices[v];
    centroid /= (float) mesh.vertices.size();

    //Calcul de l'ACP sur les points du maillage
    Vec3 eigenvalues; //Vec3 contenant les valeurs propres
    Vec3 eigenvector_1, eigenvector_2, eigenvector_3; //Vec3 contenant les vecteurs propres i.e. les directions principales de l'objet

    compute_principal_axis(mesh.vertices, eigenvalues, eigenvector_1, eigenvector_2, eigenvector_3);

    //Création du repère orthonormal aligné sur les direction principales
    basis = Basis(centroid, // origin : centroid de la forme
                  eigenvector_1,  //Composant principale avec la valeur propre la plus grande : direction principale
                  eigenvector_2,  //Composant principale avec la valeur propre intermédiaire
                  eigenvector_3); //Composant principale avec la valeur propre la plus petite

    for (unsigned int i = 0; i < 3; i++) {
        planes[i].point = centroid;
        planes[i].normal = basis.normalized_axis(i);
    }

    // Calcul des variances sur les sous espaces principaux:
    Vec3 variance(0., 0., 0.);
    for (unsigned int i = 0; i < 3; i++) {
        //Projection des points sur l'axe courant (utiliser le vecteur normaliser pour chaque axe)
        std::vector <Vec3> projection_on_basis;
        project(mesh.vertices, projection_on_basis, basis.origin(), basis.normalized_axis(i));

        // Question 2.5: TODO Compléter
        // variance[i] =...
    }

    // Comparaison de la variance et des racines des valeurs propres
    std::cout << "Variance " << sqrt(variance[0]) << " " << sqrt(variance[1]) << " " << sqrt(variance[2]) << std::endl;
    std::cout << "Racine des valeurs propres " << sqrt(eigenvalues[0]) << " " << sqrt(eigenvalues[1]) << " "
              << sqrt(eigenvalues[2]) << std::endl;

    // Question 2.4:  TODO Expliquer le facteur d'échelle pour l'ellispoide
    float sqrt_factor = 2.;
    //calcul de l'ellipsoide alignée sur les directions principales
    setEllipsoide(ellipsoide,
                  centroid,
                  sqrt_factor,
                  eigenvalues, //valeurs propres
                  eigenvector_1, eigenvector_2, eigenvector_3); //vecteurs propres

    // Question 1.1: TODO, Appliquer une matrice de transformation aux points
    // Changer la matrice pour appliquer une transformation aux sommets du maillage gris
    // Essayer une mise à l'échelle non uniforme

    //Example de transformation :
    Vec3 scale(1., 1., 1.); //Mise à l'échelle non uniforme
    Mat3 scale_matrix(scale[0], 0., 0.,
                      0., scale[1], 0.,
                      0., 0., scale[2]); //Matrice de transformation de mise à l'échelle
    float x_angle = 0. * M_PI / 180.;
    Mat3 x_rotation(1., 0., 0.,
                    0., cos(x_angle), -sin(x_angle),
                    0., sin(x_angle), cos(x_angle));

    float y_angle = 0. * M_PI / 180.;
    Mat3 y_rotation(cos(y_angle), 0., sin(y_angle),
                    0., 1., 0.,
                    -sin(y_angle), 0., cos(y_angle));

    float z_angle = 0. * M_PI / 180.;
    Mat3 z_rotation(cos(z_angle), -sin(z_angle), 0.,
                    sin(z_angle), cos(z_angle), 0.,
                    0., 0., 1.);

    //Cumulate transformation by matrix multiplications
    //Mat3 transformation = z_rotation * y_rotation * x_rotation * scale_matrix;
    Mat3 transformation = Mat3(1., 1., 0., 1., 1., 0.5, 1., 0., 1.);
    //Add a translation
    Vec3 translation = Vec3(1., 0., 0.);

    //Compute transform obejct with transformation matrix (roation and scale) and translation
    Transform mesh_transform (transformation, translation);

    // Init transformed mesh, basis, planes and ellispoide to the input data
    transformed_mesh = Mesh(mesh);
    transformed_basis = basis;
    for (unsigned int i = 0; i < 3; i++) transformed_planes[i] = planes[i];
    transformed_ellipsoide = Mesh(ellipsoide);

    //Apply transformation to data (mesh, basis, planes, ellispoide)
    update_transformed(mesh_transform);

    glutMainLoop();
    return EXIT_SUCCESS;
}
