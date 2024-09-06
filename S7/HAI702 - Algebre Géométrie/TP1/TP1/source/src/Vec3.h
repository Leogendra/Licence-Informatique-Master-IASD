#ifndef VEC3_H
#define VEC3_H

#include <cmath>
#include <iostream>
#include <cassert>

#include <gsl/gsl_matrix.h>
#include <gsl/gsl_eigen.h>
#include <gsl/gsl_linalg.h>
// you need to add the following libraries to your project : gsl, gslcblas

class Vec3 {
private:
    float mVals[3];
public:
    Vec3() {}

    Vec3(float x, float y, float z) {
        mVals[0] = x;
        mVals[1] = y;
        mVals[2] = z;
    }

    float &operator[](unsigned int c) { return mVals[c]; }

    float operator[](unsigned int c) const { return mVals[c]; }

    void operator=(Vec3 const &other) {
        mVals[0] = other[0];
        mVals[1] = other[1];
        mVals[2] = other[2];
    }

    //Retourne la norme au carré d'un vecteur
    float squareLength() const {
        return mVals[0] * mVals[0] + mVals[1] * mVals[1] + mVals[2] * mVals[2];
    }

    //Calcul de la norme d'un vecteur
    float length() const { return sqrt(squareLength()); }

    //Normalisation d'un vecteur
    void normalize() {
        float L = length();
        mVals[0] /= L;
        mVals[1] /= L;
        mVals[2] /= L;
    }

    //Calculer le produit scalaire entre 2 vecteurs
    static float dot(Vec3 const &a, Vec3 const &b) {
        float res = a[0]*b[0] + a[1]*b[1] + a[2]*b[2];
        return res;
    }


    //Calculer le produit vectoriel entre 2 vecteurs
    static Vec3 cross(Vec3 const &u, Vec3 const &v) {
        float x = u[1]*v[2] - u[2]*v[1];
        float y = -(u[0]*v[2] - u[2]*v[0]);
        float z = u[0]*v[1] - u[1]*v[0];
        Vec3 res = Vec3(x, y, z);
        return res;
    }

    void operator+=(Vec3 const &other) {
        mVals[0] += other[0];
        mVals[1] += other[1];
        mVals[2] += other[2];
    }

    void operator-=(Vec3 const &other) {
        mVals[0] -= other[0];
        mVals[1] -= other[1];
        mVals[2] -= other[2];
    }

    void operator*=(float s) {
        mVals[0] *= s;
        mVals[1] *= s;
        mVals[2] *= s;
    }

    void operator/=(float s) {
        mVals[0] /= s;
        mVals[1] /= s;
        mVals[2] /= s;
    }

};

static inline Vec3 operator+(Vec3 const &a, Vec3 const &b) {
    return Vec3(a[0] + b[0], a[1] + b[1], a[2] + b[2]);
}

static inline Vec3 operator-(Vec3 const &a, Vec3 const &b) {
    return Vec3(a[0] - b[0], a[1] - b[1], a[2] - b[2]);
}

static inline Vec3 operator*(float a, Vec3 const &b) {
    return Vec3(a * b[0], a * b[1], a * b[2]);
}

static inline Vec3 operator/(Vec3 const &a, float b) {
    return Vec3(a[0] / b, a[1] / b, a[2] / b);
}

static inline std::ostream &operator<<(std::ostream &s, Vec3 const &p) {
    s << p[0] << " " << p[1] << " " << p[2];
    return s;
}

static inline std::istream &operator>>(std::istream &s, Vec3 &p) {
    s >> p[0] >> p[1] >> p[2];
    return s;
}




class Mat3 {
public:
    ////////////         CONSTRUCTORS          //////////////
    Mat3() {
        vals[0] = 0;
        vals[1] = 0;
        vals[2] = 0;
        vals[3] = 0;
        vals[4] = 0;
        vals[5] = 0;
        vals[6] = 0;
        vals[7] = 0;
        vals[8] = 0;
    }

    Mat3(float v1, float v2, float v3, float v4, float v5, float v6, float v7, float v8, float v9) {
        vals[0] = v1;
        vals[1] = v2;
        vals[2] = v3;
        vals[3] = v4;
        vals[4] = v5;
        vals[5] = v6;
        vals[6] = v7;
        vals[7] = v8;
        vals[8] = v9;
    }

    Mat3(const Mat3 &m) {
        for (int i = 0; i < 3; ++i)
            for (int j = 0; j < 3; ++j)
                (*this)(i, j) = m(i, j);
    }


    // ---------- STATIC STANDARD MATRICES ---------- //
    inline static Mat3 Identity() { return Mat3(1, 0, 0, 0, 1, 0, 0, 0, 1); }

    inline static Mat3 Zero() { return Mat3(0, 0, 0, 0, 0, 0, 0, 0, 0); }

    // Multiplication de matrice avec un Vec3 : m.p
    //--> application d'un matrice de rotation à un point ou un vecteur
    Vec3 operator*(const Vec3 &p) {
        //Pour acceder a un element de la matrice (*this)(i,j) et du point p[i]
        Vec3 res = Vec3(0, 0, 0);
        for (int i=0; i<3; i++) {
            for (int j=0; j<3; j++) {
                res[i] += p[j]*(*this)(j,i);
            }
            // res[i] *= 0.5;
        }
        // res[0] -= 1;
        // res[1] -= 0.1;
        // res[2] -= 0.5;
        return res;
    }

    Mat3 operator*(const Mat3 &m2) { // calcul du produit matriciel m1.m2
        //Pour acceder a un element de la premiere matrice (*this)(i,j)
        Mat3 res = Mat3();
        for (int i=0; i<3; i++) {
            for (int j=0; j<3; j++) {
                res(i,j) = (*this)(i,0)*m2(0,j) + (*this)(i,1)*m2(1,j) + (*this)(i,2)*m2(2,j);
            }
        }
        return res;
    }

    bool isnan() const {
        return std::isnan(vals[0]) || std::isnan(vals[1]) || std::isnan(vals[2])
                || std::isnan(vals[3]) || std::isnan(vals[4]) || std::isnan(vals[5])
                || std::isnan(vals[6]) || std::isnan(vals[7]) || std::isnan(vals[8]);
    }

    void operator=(const Mat3 &m) {
        for (int i = 0; i < 3; ++i)
            for (int j = 0; j < 3; ++j)
                (*this)(i, j) = m(i, j);
    }

    void operator+=(const Mat3 &m) {
        for (int i = 0; i < 3; ++i)
            for (int j = 0; j < 3; ++j)
                (*this)(i, j) += m(i, j);
    }

    void operator-=(const Mat3 &m) {
        for (int i = 0; i < 3; ++i)
            for (int j = 0; j < 3; ++j)
                (*this)(i, j) -= m(i, j);
    }

    void operator/=(double s) {
        for (unsigned int c = 0; c < 9; ++c)
            vals[c] /= s;
    }

    Mat3 operator-(const Mat3 &m2) {
        return Mat3((*this)(0, 0) - m2(0, 0), (*this)(0, 1) - m2(0, 1), (*this)(0, 2) - m2(0, 2),
                    (*this)(1, 0) - m2(1, 0), (*this)(1, 1) - m2(1, 1), (*this)(1, 2) - m2(1, 2),
                    (*this)(2, 0) - m2(2, 0), (*this)(2, 1) - m2(2, 1), (*this)(2, 2) - m2(2, 2));
    }

    Mat3 operator+(const Mat3 &m2) {
        return Mat3((*this)(0, 0) + m2(0, 0), (*this)(0, 1) + m2(0, 1), (*this)(0, 2) + m2(0, 2),
                    (*this)(1, 0) + m2(1, 0), (*this)(1, 1) + m2(1, 1), (*this)(1, 2) + m2(1, 2),
                    (*this)(2, 0) + m2(2, 0), (*this)(2, 1) + m2(2, 1), (*this)(2, 2) + m2(2, 2));
    }

    Mat3 operator/(float s) {
        return Mat3((*this)(0, 0) / s, (*this)(0, 1) / s, (*this)(0, 2) / s, (*this)(1, 0) / s, (*this)(1, 1) / s,
                    (*this)(1, 2) / s, (*this)(2, 0) / s, (*this)(2, 1) / s, (*this)(2, 2) / s);
    }

    Mat3 operator*(float s) {
        return Mat3((*this)(0, 0) * s, (*this)(0, 1) * s, (*this)(0, 2) * s, (*this)(1, 0) * s, (*this)(1, 1) * s,
                    (*this)(1, 2) * s, (*this)(2, 0) * s, (*this)(2, 1) * s, (*this)(2, 2) * s);
    }

    ////////        ACCESS TO COORDINATES      /////////
    float operator()(unsigned int i, unsigned int j) const { return vals[3 * i + j]; }

    float &operator()(unsigned int i, unsigned int j) { return vals[3 * i + j]; }

    Mat3 operator-() const {
        return Mat3(-vals[0], -vals[1], -vals[2], -vals[3], -vals[4], -vals[5], -vals[6], -vals[7], -vals[8]);
    }


private:
    float vals[9];
    // will be noted as :
    // 0 1 2
    // 3 4 5
    // 6 7 8
};


inline static
Mat3 operator*(float s, const Mat3 &m) {
    return Mat3(m(0, 0) * s, m(0, 1) * s, m(0, 2) * s, m(1, 0) * s, m(1, 1) * s, m(1, 2) * s, m(2, 0) * s, m(2, 1) * s,
                m(2, 2) * s);
}


inline static std::ostream &operator<<(std::ostream &s, Mat3 const &m) {
    s << m(0, 0) << " \t" << m(0, 1) << " \t" << m(0, 2) << std::endl << m(1, 0) << " \t" << m(1, 1) << " \t" << m(1, 2)
      << std::endl << m(2, 0) << " \t" << m(2, 1) << " \t" << m(2, 2) << std::endl;
    return s;
}


#endif
