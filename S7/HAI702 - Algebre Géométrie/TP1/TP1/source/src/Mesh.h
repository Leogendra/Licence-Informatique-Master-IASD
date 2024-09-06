#ifndef MESH_H
#define MESH_H

struct Mesh {
    std::vector <Vec3> vertices; //array of mesh vertices positions
    std::vector <Vec3> normals; //array of vertices normals useful for the display
    std::vector <Triangle> triangles; //array of mesh triangles
    std::vector <Vec3> triangle_normals; //triangle normals to display face normals

    //Compute face normals for the display
    void computeTrianglesNormals() {
        triangle_normals.clear();
        for (int i=0; i<(int)triangles.size(); i++) { // Iterer sur les triangles du maillage

            Vec3 e10 = vertices[triangles[i][1]] - vertices[triangles[i][0]];
            Vec3 e20 = vertices[triangles[i][2]] - vertices[triangles[i][0]];

            Vec3 res = Vec3::cross(e10, e20);
            res.normalize();
            triangle_normals.push_back(res);
        }
    }

    //Compute vertices normals as the average of its incident faces normals
    void computeVerticesNormals() {
        normals.clear();
        //Initializer le vecteur normals taille vertices.size() avec Vec3(0., 0., 0.)
        for (int i=0; i<(int)vertices.size(); i++) {
            normals.push_back(Vec3(0, 0, 0));
        }
        
        for (int i=0; i<(int)triangles.size(); i++) { // Iterer sur les triangles du maillage
            for (int j=0; j<3; j++) { // Ajouter la normal au triangle à celle de chacun des sommets
                normals[triangles[i][j]] += triangle_normals[i];
            }
        }
        
        for (int i=0; i<(int)normals.size(); i++) { // Iterer sur les normales et les normaliser
            normals[i].normalize();
        }
    }

    void computeNormals() {
        computeTrianglesNormals();
        computeVerticesNormals();
    }

    Mesh(){}

    Mesh( Mesh const& i_mesh):
        vertices(i_mesh.vertices),
        normals(i_mesh.normals),
        triangles(i_mesh.triangles),
        triangle_normals(i_mesh.triangle_normals)
    {}

    Mesh( std::vector <Vec3> const& i_vertices, std::vector <Triangle> const& i_triangles):
        vertices(i_vertices),
        triangles(i_triangles)
    {
        computeNormals();
    }

};


#endif // MESH_H
