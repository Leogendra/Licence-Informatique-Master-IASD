#ifndef TRIANGLE_H
#define TRIANGLE_H


struct Triangle {
    inline Triangle() {
        v[0] = v[1] = v[2] = 0;
    }

    inline Triangle(const Triangle &t) {
        v[0] = t.v[0];
        v[1] = t.v[1];
        v[2] = t.v[2];
    }

    inline Triangle(unsigned int v0, unsigned int v1, unsigned int v2) {
        v[0] = v0;
        v[1] = v1;
        v[2] = v2;
    }

    unsigned int &operator[](unsigned int iv) { return v[iv]; }

    unsigned int operator[](unsigned int iv) const { return v[iv]; }

    inline virtual ~Triangle() {}

    inline Triangle &operator=(const Triangle &t) {
        v[0] = t.v[0];
        v[1] = t.v[1];
        v[2] = t.v[2];
        return (*this);
    }

    // membres indices des sommets du triangle:
    unsigned int v[3];
};

#endif // TRIANGLE_H
