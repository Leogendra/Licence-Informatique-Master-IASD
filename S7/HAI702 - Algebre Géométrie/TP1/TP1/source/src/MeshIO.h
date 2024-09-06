#ifndef MESHIO_H
#define MESHIO_H
// ------------------------------------
// File I/O
// ------------------------------------
bool saveOFF(const std::string &filename,
             std::vector <Vec3> &i_vertices,
             std::vector <Vec3> &i_normals,
             std::vector <Triangle> &i_triangles,
             std::vector <Vec3> &i_triangle_normals,
             bool save_normals = true) {
    std::ofstream myfile;
    myfile.open(filename.c_str());
    if (!myfile.is_open()) {
        std::cout << filename << " cannot be opened" << std::endl;
        return false;
    }

    myfile << "OFF" << std::endl;

    unsigned int n_vertices = i_vertices.size(), n_triangles = i_triangles.size();
    myfile << n_vertices << " " << n_triangles << " 0" << std::endl;

    for (unsigned int v = 0; v < n_vertices; ++v) {
        myfile << i_vertices[v][0] << " " << i_vertices[v][1] << " " << i_vertices[v][2] << " ";
        if (save_normals) myfile << i_normals[v][0] << " " << i_normals[v][1] << " " << i_normals[v][2] << std::endl;
        else myfile << std::endl;
    }
    for (unsigned int f = 0; f < n_triangles; ++f) {
        myfile << 3 << " " << i_triangles[f][0] << " " << i_triangles[f][1] << " " << i_triangles[f][2] << " ";
        if (save_normals)
            myfile << i_triangle_normals[f][0] << " " << i_triangle_normals[f][1] << " " << i_triangle_normals[f][2];
        myfile << std::endl;
    }
    myfile.close();
    return true;
}

void openOFF(std::string const &filename,
             std::vector <Vec3> &o_vertices,
             std::vector <Vec3> &o_normals,
             std::vector <Triangle> &o_triangles,
             std::vector <Vec3> &o_triangle_normals,
             bool load_normals = true) {
    std::ifstream myfile;
    myfile.open(filename.c_str());
    if (!myfile.is_open()) {
        std::cout << filename << " cannot be opened" << std::endl;
        return;
    }

    std::string magic_s;

    myfile >> magic_s;

    if (magic_s != "OFF") {
        std::cout << magic_s << " != OFF :   We handle ONLY *.off files." << std::endl;
        myfile.close();
        exit(1);
    }

    int n_vertices, n_faces, dummy_int;
    myfile >> n_vertices >> n_faces >> dummy_int;

    o_vertices.clear();
    o_normals.clear();

    for (int v = 0; v < n_vertices; ++v) {
        float x, y, z;

        myfile >> x >> y >> z;
        o_vertices.push_back(Vec3(x, y, z));

        if (load_normals) {
            myfile >> x >> y >> z;
            o_normals.push_back(Vec3(x, y, z));
        }
    }

    o_triangles.clear();
    o_triangle_normals.clear();
    for (int f = 0; f < n_faces; ++f) {
        int n_vertices_on_face;
        myfile >> n_vertices_on_face;

        if (n_vertices_on_face == 3) {
            unsigned int _v1, _v2, _v3;
            myfile >> _v1 >> _v2 >> _v3;

            o_triangles.push_back(Triangle(_v1, _v2, _v3));

            if (load_normals) {
                float x, y, z;
                myfile >> x >> y >> z;
                o_triangle_normals.push_back(Vec3(x, y, z));
            }
        } else if (n_vertices_on_face == 4) {
            unsigned int _v1, _v2, _v3, _v4;
            myfile >> _v1 >> _v2 >> _v3 >> _v4;

            o_triangles.push_back(Triangle(_v1, _v2, _v3));
            o_triangles.push_back(Triangle(_v1, _v3, _v4));
            if (load_normals) {
                float x, y, z;
                myfile >> x >> y >> z;
                o_triangle_normals.push_back(Vec3(x, y, z));
            }

        } else {
            std::cout << "We handle ONLY *.off files with 3 or 4 vertices per face" << std::endl;
            myfile.close();
            exit(1);
        }
    }

}
#endif // MESHIO_H
