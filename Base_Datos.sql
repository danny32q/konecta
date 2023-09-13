PGDMP     &                    {            postgres    15.4    15.4 !               0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false                       0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false                       1262    5    postgres    DATABASE     ~   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Spanish_Colombia.1252';
    DROP DATABASE postgres;
                postgres    false                       0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                   postgres    false    3353                        3079    16384 	   adminpack 	   EXTENSION     A   CREATE EXTENSION IF NOT EXISTS adminpack WITH SCHEMA pg_catalog;
    DROP EXTENSION adminpack;
                   false                       0    0    EXTENSION adminpack    COMMENT     M   COMMENT ON EXTENSION adminpack IS 'administrative functions for PostgreSQL';
                        false    2            �            1259    16432 	   categoria    TABLE     x   CREATE TABLE public.categoria (
    idcategoria integer NOT NULL,
    nombrecategoria character varying(50) NOT NULL
);
    DROP TABLE public.categoria;
       public         heap    postgres    false            �            1259    16431    categoria_id_seq    SEQUENCE     �   CREATE SEQUENCE public.categoria_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.categoria_id_seq;
       public          postgres    false    216                       0    0    categoria_id_seq    SEQUENCE OWNED BY     N   ALTER SEQUENCE public.categoria_id_seq OWNED BY public.categoria.idcategoria;
          public          postgres    false    215            �            1259    16441    producto    TABLE     5  CREATE TABLE public.producto (
    idproducto integer NOT NULL,
    nombre_producto character varying(255) NOT NULL,
    referencia character varying(50) NOT NULL,
    precio integer NOT NULL,
    peso integer NOT NULL,
    idcategoria integer,
    stock integer NOT NULL,
    fecha_creacion date NOT NULL
);
    DROP TABLE public.producto;
       public         heap    postgres    false            �            1259    16440    producto_id_seq    SEQUENCE     �   CREATE SEQUENCE public.producto_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.producto_id_seq;
       public          postgres    false    218                       0    0    producto_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.producto_id_seq OWNED BY public.producto.idproducto;
          public          postgres    false    217            �            1259    16471    ventas    TABLE     �   CREATE TABLE public.ventas (
    idventa integer NOT NULL,
    idproductovendido integer,
    cantidadvendida integer,
    fechaventa date
);
    DROP TABLE public.ventas;
       public         heap    postgres    false            �            1259    16470    ventas_idventa_seq    SEQUENCE     �   CREATE SEQUENCE public.ventas_idventa_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.ventas_idventa_seq;
       public          postgres    false    220                       0    0    ventas_idventa_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.ventas_idventa_seq OWNED BY public.ventas.idventa;
          public          postgres    false    219            p           2604    16435    categoria idcategoria    DEFAULT     u   ALTER TABLE ONLY public.categoria ALTER COLUMN idcategoria SET DEFAULT nextval('public.categoria_id_seq'::regclass);
 D   ALTER TABLE public.categoria ALTER COLUMN idcategoria DROP DEFAULT;
       public          postgres    false    216    215    216            q           2604    16444    producto idproducto    DEFAULT     r   ALTER TABLE ONLY public.producto ALTER COLUMN idproducto SET DEFAULT nextval('public.producto_id_seq'::regclass);
 B   ALTER TABLE public.producto ALTER COLUMN idproducto DROP DEFAULT;
       public          postgres    false    218    217    218            r           2604    16474    ventas idventa    DEFAULT     p   ALTER TABLE ONLY public.ventas ALTER COLUMN idventa SET DEFAULT nextval('public.ventas_idventa_seq'::regclass);
 =   ALTER TABLE public.ventas ALTER COLUMN idventa DROP DEFAULT;
       public          postgres    false    219    220    220                      0    16432 	   categoria 
   TABLE DATA           A   COPY public.categoria (idcategoria, nombrecategoria) FROM stdin;
    public          postgres    false    216   �$                 0    16441    producto 
   TABLE DATA           }   COPY public.producto (idproducto, nombre_producto, referencia, precio, peso, idcategoria, stock, fecha_creacion) FROM stdin;
    public          postgres    false    218   �$                 0    16471    ventas 
   TABLE DATA           Y   COPY public.ventas (idventa, idproductovendido, cantidadvendida, fechaventa) FROM stdin;
    public          postgres    false    220   X%                  0    0    categoria_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.categoria_id_seq', 14, true);
          public          postgres    false    215                        0    0    producto_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.producto_id_seq', 14, true);
          public          postgres    false    217            !           0    0    ventas_idventa_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.ventas_idventa_seq', 15, true);
          public          postgres    false    219            t           2606    16439 '   categoria categoria_nombrecategoria_key 
   CONSTRAINT     m   ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_nombrecategoria_key UNIQUE (nombrecategoria);
 Q   ALTER TABLE ONLY public.categoria DROP CONSTRAINT categoria_nombrecategoria_key;
       public            postgres    false    216            v           2606    16437    categoria categoria_pkey 
   CONSTRAINT     _   ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (idcategoria);
 B   ALTER TABLE ONLY public.categoria DROP CONSTRAINT categoria_pkey;
       public            postgres    false    216            x           2606    16446    producto producto_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.producto
    ADD CONSTRAINT producto_pkey PRIMARY KEY (idproducto);
 @   ALTER TABLE ONLY public.producto DROP CONSTRAINT producto_pkey;
       public            postgres    false    218            z           2606    16448     producto producto_referencia_key 
   CONSTRAINT     a   ALTER TABLE ONLY public.producto
    ADD CONSTRAINT producto_referencia_key UNIQUE (referencia);
 J   ALTER TABLE ONLY public.producto DROP CONSTRAINT producto_referencia_key;
       public            postgres    false    218            |           2606    16476    ventas ventas_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_pkey PRIMARY KEY (idventa);
 <   ALTER TABLE ONLY public.ventas DROP CONSTRAINT ventas_pkey;
       public            postgres    false    220            }           2606    16464    producto fk_categoria    FK CONSTRAINT     �   ALTER TABLE ONLY public.producto
    ADD CONSTRAINT fk_categoria FOREIGN KEY (idcategoria) REFERENCES public.categoria(idcategoria);
 ?   ALTER TABLE ONLY public.producto DROP CONSTRAINT fk_categoria;
       public          postgres    false    218    216    3190            ~           2606    16449 "   producto producto_categoriaid_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.producto
    ADD CONSTRAINT producto_categoriaid_fkey FOREIGN KEY (idcategoria) REFERENCES public.categoria(idcategoria);
 L   ALTER TABLE ONLY public.producto DROP CONSTRAINT producto_categoriaid_fkey;
       public          postgres    false    216    218    3190                       2606    16477 $   ventas ventas_idproductovendido_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_idproductovendido_fkey FOREIGN KEY (idproductovendido) REFERENCES public.producto(idproducto);
 N   ALTER TABLE ONLY public.ventas DROP CONSTRAINT ventas_idproductovendido_fkey;
       public          postgres    false    220    218    3192               ?   x�3�t�IM.):�9/39��Ѐ�%�� 5�8Q��А�'��43%��Ј�/���41�+F��� ���         ]   x�U�;@@�������1�v j�����+џ1����s����2�2Q
��-�72��ya����84i��8�O �;�h��][P         ;   x�U̱�0���ņH��K��#r����P�+�E5ȗ4��Ԑ���oG�<x*     